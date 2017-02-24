<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App\Http\Controllers;

use App\Fahrer;
use App\Fahrrad;
use App\Modus;
use App\Statistik;
use App\StatistikMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class FahrradController
 *
 * Diese Klasse stellt Methoden bereit um Fahrraddatensätze
 * * auszugeben
 * * zu ändern
 * * eine Zuordnung herzustellen
 * * eine Zuordnung zu löschen
 *
 * @package App\Http\Controllers
 */
class FahrradController extends Controller
{
    /**
     * Update des Fahrraddatensatzes.
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrrad-ID
     *
     * Zusätzlich die zu verändernden Felder:
     *
     * * strecke_update_drehmoment (float) (optional)
     * * * Das Drehmoment hängt von der gewählten Strecke ab und kann mit dem Parameter verändert werden
     * * modus_id (int)
     * * modus_value (int)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  \App\Fahrrad $fahrrad
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung:
     *  Fahrraddatensatz (JSON Array)
     */
    public function update(Request $request, \App\Fahrrad $fahrrad)
    {
        if($request->has("modus_id")){
            $fahrrad->resetData();

            $modus_id = Input::get("modus_id");
            $fahrrad->modus_id = $modus_id;

            if($modus_id != 1){ // Nicht Strecke
                if($request->has("modus_value")){
                    $modus_value = Input::get("modus_value");
                }else{
                    $modus_value = 200; // Standardwert
                }

                if($modus_id == 2){ // Drehmoment
                    $fahrrad->sollLeistung = null;
                    $fahrrad->sollDrehmoment = $modus_value;
                }elseif($modus_id == 3){ // Leistung
                    $fahrrad->sollDrehmoment = null;
                    $fahrrad->sollLeistung = $modus_value;
                }
            }
        }

        // Eine Strecke hat für jeden Abschnitt verschiedene Drehmomente
        if($request->has("strecke_update_drehmoment")){
            $fahrrad->sollDrehmoment = Input::get("strecke_update_drehmoment");
        }

        $fahrrad->touch();
        $fahrrad->save();

        return $fahrrad;
    }

    /**
     * Stellt eine Zuordnung von Fahrer zu Fahrrad her.
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrrad-ID
     * * Fahrer-ID
     *
     * @param Request $request
     * @param Int $fahrrad_id
     * @param Int $fahrer_id
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung bei Erfolg:
     * * { "fahrrad" : Fahrraddatensatz (JSON Array), "fahrer" : Fahrerdatensatz (JSON Array), "modi" : Alle Modi (JSON Array) }, Status 200
     *
     * Rückmeldung bei Fehler:
     * * Fahrer schon zugeordnet:
     * { msg: "Fahrer schon zugeordnet", "err" : 1 }, Status 200
     *
     * * Fahrrad ist besetzt:
     * { msg: "Fahrrad ist besetzt", "err" : 2 }, Status 200
     *
     * * Fahrrad nicht gefunden:
     * { msg: "Fahrrad nicht gefunden", "err" : 3 }, Status 200
     *
     * * Fahrer nicht gefunden:
     * { msg: "Fahrer nicht gefunden", "err" : 4 }, Status 200
     */
    public function zuordnungHerstellen(Request $request, $fahrrad_id, $fahrer_id)
    {
        $fahrrad = Fahrrad::whereId($fahrrad_id)->first();
        if(!$fahrrad){
            return response()->json(["msg" => "Fahrrad nicht gefunden", "err" => 3], 200);
        }

        $fahrer = Fahrer::whereId($fahrer_id)->first();
        if(!$fahrer){
            return response()->json(["msg" => "Fahrer nicht gefunden", "err" => 4], 200);
        }

        $fahrer_fahrrad = Fahrrad::whereFahrerId($fahrer->id)->first();
        if($fahrer_fahrrad){
            return response()->json(["msg" => "Fahrer schon zugeordnet", "err" => 1], 200);
        }else{
            if($fahrrad->fahrer_id == null){

                if(Input::has("modus_id")){
                    $fahrrad->modus_id = Input::get("modus_id");
                    if($fahrrad->modus_id == 2){
                        $fahrrad->sollDrehmoment = 6;
                    }elseif ($fahrrad->modus_id == 3){
                        $fahrrad->sollLeistung = 60;
                    }
                }

                $fahrer->vorgang = str_random(20);
                $fahrer->touch();
                $fahrer->save();

                $fahrrad->strecke = 0;
                $fahrrad->fahrer_id = $fahrer->id;
                $fahrrad->zugeordnet_at = time();

                $fahrrad->touch();
                $fahrrad->save();

                return response()->json([
                    "fahrrad" => $fahrrad,
                    "fahrer" => $fahrer,
                    "modi" => Modus::all()
                ], 200);
            }else{
                return response()->json(["msg" => "Fahrrad ist besetzt", "err" => 2], 200);
            }
        }
    }

    /**
     * Löscht die Zuordnung eines Fahrers zum angegebenen Fahrrad
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrrad-ID (int)
     *
     * @param Fahrrad $fahrrad
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung bei Erfolg:
     * { msg: "ok", "email" : Boolean=Statistik-Email wurde gesendet }, Status 200
     * * Rückmeldung bei Fehler:
     * { msg: "error" }, Status 400
     */
    public function zuordnungLoeschen(\App\Fahrrad $fahrrad)
    {
        $fahrer = Fahrer::whereId($fahrrad->fahrer_id)->first();
        if($fahrer){
            Statistik::addEntry($fahrer, $fahrrad);

            $email_sent = false;
//            if(!empty($fahrer->email)){
//                if(StatistikMail::sendMail($fahrer)){
//                    $email_sent = true;
//                }
//            }

            // Reset
            $fahrrad->fahrer_id = null;
            $fahrrad->zugeordnet_at = null;
            $fahrrad->modus_id = 1;
            $fahrrad->resetData();

            $fahrer->vorgang = null;

            // Update
            $fahrrad->touch();
            $fahrrad->save();

            $fahrer->touch();
            $fahrer->save();

            // Return success
            if($fahrrad->fahrer_id == null){
                return response()->json(["msg" => "ok", "email" => $email_sent], 200);
            }
        }

        return response()->json(["msg" => "error"], 400);
    }

    /**
     * Gibt die Daten zu allen Fahrrädern, inkl. Modi und Fahrerinformationen zurück
     *
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung:
     * * { data: { "fahrrad" : Fahrraddatensatz  (JSON Array) } }, Status 200
     */
    public function getData()
    {
        return response()->json([ "data" => ["fahrrad" => Fahrrad::with("modus")->with("fahrer")->get()] ], 200);
    }
}
