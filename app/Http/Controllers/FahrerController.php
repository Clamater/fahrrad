<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App\Http\Controllers;

use App\Fahrer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Flysystem\Exception;

/**
 * Class FahrerController
 *
 * Diese Klasse stellt Methoden bereit um Fahrer
 * * anzuzeigen
 * * hinzuzufügen
 * * zu löschen
 * * zu ändern
 * * alle Fahrernamen auszugeben.
 *
 * @package App\Http\Controllers
 */
class FahrerController extends Controller
{
    /**
     * Erstellt einen neuen Fahrer und speichert diesen in der Datenbank
     *
     * Es wird folgender Input im Request erwartet:
     * * Name (string)
     * * E-Mail (string) (optional)
     * * Gewicht in kg (float) (optional, Standard 80)
     * * Größe in Metern (float) (optional, Standard 1.80)
     * * Wunschbetriebsmodus (int) (optional, Standard 1 [Strecke])
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse, Status 200
     * * Rückmeldung bei Erfolg:
     * { "msg" : "ok", "err" : 0, "fahrer" : Fahrerdatensatz }
     * * Rückmeldung bei Fehler:
     * { "msg" : "Name schon vorhanden", "err" : 1 }
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required"
        ]);

        $name = Input::get("name");
        $fahrer_mit_name = Fahrer::whereName($name)->first();

        if(!$fahrer_mit_name){ // Name noch nicht vorhanden
            $fahrer = Fahrer::create([
                "name" => $name
            ]);

            $id = $fahrer->id;

            if($request->has("email")){
                $fahrer->email = Input::get("email");
            }

            if($request->has("gewicht")){
                $gewicht = Input::get("gewicht");
                $gewicht = str_replace(".", "", $gewicht);
                $gewicht = str_replace(",", "", $gewicht);
                $fahrer->gewicht = $gewicht;
            }

            if($request->has("groesse")){
                $groesse = Input::get("groesse");
                $groesse = str_replace(",", ".", $groesse);
                $fahrer->groesse = $groesse;
            }

            if($request->has("betriebsmodus")){
                $fahrer->modus_id = Input::get("betriebsmodus");
            }

            $fahrer->save();
            $fahrer->touch();

            return response()->json(["msg" => "ok", "err" => 0, "fahrer" => Fahrer::whereId($id)->first()], 200);
        }

        return response()->json(["msg" => "Name schon vorhanden", "err" => 1], 200);
    }

    /**
     * Gibt einen vorhandenen Fahrerdatensatz zurück
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrer-ID (int)
     *
     * @param  int  \App\Fahrer $fahrer
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung bei Erfolg:
     *  Fahrerdatensatz (JSON Array)
     * * Rückmeldung bei Fehler:
     * Exception
     */
    public function show(\App\Fahrer $fahrer)
    {
        return $fahrer;
    }

    /**
     * Update eines vorhandenen Fahrerdatensatzes
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrer-ID (int)
     *
     * Zusätzlich die zu verändernden Felder:
     * * Name (string) (optional)
     * * E-Mail (string) (optional)
     * * Gewicht in kg (float) (optional)
     * * Größe in Metern (float) (optional)
     * * Wunschbetriebsmodus (int) (optional)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  \App\Fahrer $fahrer
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung bei Erfolg:
     *  Fahrerdatensatz (JSON Array)
     * * Rückmeldung bei Fehler:
     * Exception
     */
    public function update(Request $request, \App\Fahrer $fahrer)
    {
        if($request->has("name")){
            $fahrer->name = Input::get("name");
        }

        if($request->has("email")){
            if(Input::get("email") == "_"){
                $fahrer->email = null;
            }else{
                $fahrer->email = Input::get("email");
            }
        }

        if($request->has("gewicht")){
            $fahrer->gewicht = Input::get("gewicht");
        }

        if($request->has("groesse")){
            $fahrer->groesse = Input::get("groesse");
        }

        if($request->has("modus_id")){
            $fahrer->modus_id = Input::get("modus_id");
        }

        $fahrer->save();
        $fahrer->touch();

        return $fahrer;
    }

    /**
     * Entfernt einen Fahrer aus der Datenbank
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrer-ID (int)
     *
     * @param  int  \App\Fahrer $fahrer
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung bei Erfolg:
     * { msg: "ok" }, Status 200
     * * Rückmeldung bei Fehler:
     * { msg: "error" }, Status 400
     */
    public function destroy(\App\Fahrer $fahrer)
    {
        $fahrradId = null;
        if($fahrer->fahrrad()){
            $fahrradId = $fahrer->fahrrad()->pluck("id");
        }else{
            $fahrradId = null;
        }
        $fahrer->delete();

        if(!Fahrer::find($fahrer->id)){
            if($fahrradId != null){ // Beim löschen die Fahrrad ID mitgeben für Interface Update
                return response()->json(["msg" => "ok", "id" => $fahrradId], 200);
            }
            return response()->json(["msg" => "ok"], 200);
        }

        return response()->json(["msg" => "error"], 400);
    }


    /**
     * Gibt alle in der Datenbank vorhandenen Fahrernamen zurück.
     *
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung:
     * * Das "names" Feld kann leer sein.
     * * { msg: "ok", "names": Fahrernamen (JSON Array) }, Status 200
     */
    public function getAllNames()
    {
        $names = Fahrer::all("name");
        $result = [];

        foreach($names as $name){
            array_push($result, $name->name);
        }

        return response()->json(["msg" => "ok", "names" => $result], 200);
    }
}
