<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App\Http\Controllers;

use App\Abschnitt;
use App\Batterie;
use App\Fahrer;
use App\Fahrrad;
use App\Statistik;
use App\Strecke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class MainController
 *
 * Diese Klasse stellt allgemeine Funktionalität bereit, die nicht an bestimmte Klassen gebunden ist.
 *
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * Zeigt die Zentrale Ansicht an.
     *
     * Die Ansicht beinhaltet die Diagramme für
     * * Strecke
     * * Leistung
     * * Gesamtleistung
     *
     * Außerdem beinhaltet sie die Informationen zu den Fahrrädern und Statistik, sowie Highscore und die Batterieanzeige.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCentral()
    {
        return view('central.index')->with("fahrraeder", Fahrrad::all());
    }

    /**
     * Ändert den Abschnitt auf dem sich ein Fahrrad im Streckenmodus befindet.
     *
     * Es wird folgender Input im Request erwartet:
     * * Fahrrad-ID
     * * Abschnitt-ID
     *
     * Folgende Aktionen werden ausgeführt:
     * * Update von fahrrad.abschnitt_id und fahrrad.sollDrehmoment (abhängig von fahrer.gewicht und fahrer.goresse)
     * * Update und Berechnung der zurückgelegten Hoehenmeter im Abschnitt
     *
     * @param Request $request
     */
    public function setAbschnitt(Request $request)
    {
        $this->validate($request, [
            "fahrrad_id" => "required",
            "abschnitt_id" => "required"
        ]);

        $fahrrad = Fahrrad::whereId(Input::get("fahrrad_id"))->first();
        if($fahrrad && $fahrrad->modus_id == 1){
            $fahrer = Fahrer::whereId($fahrrad->fahrer_id)->first();

            Statistik::addEntry($fahrer, $fahrrad);

            $abschnitt_id      = Input::get("abschnitt_id");
            $abschnitt         = Abschnitt::whereId($abschnitt_id)->first();
            $abschnitt_folgend = Abschnitt::whereId($abschnitt_id+1)->first();
            $abschnitt_zuletzt = Abschnitt::whereId($abschnitt_id-1)->first();

            if(!empty($abschnitt) && !empty($abschnitt_folgend)){
                if(!empty($abschnitt_zuletzt)){
                    $hoehenDifferenz = $abschnitt->hoehe - $abschnitt_zuletzt->hoehe;
                    if($hoehenDifferenz > 0){
                        $fahrrad->hoehenmeter += $hoehenDifferenz;
                    }
                }

                $fahrrad->abschnitt_id = $abschnitt->id;

                // Berechnen von sollDrehmoment
                $h = $abschnitt_folgend->hoehe - $abschnitt->hoehe;
                $l = $abschnitt->laenge;

                // Steigung des Abschnitts in Prozent
                $prozent = intval($h / $l * 100);

                //$a = atan($prozent / 100);

                $aFahrer = $fahrer->groesse * 0.28; // 0.28 Korrekturfaktor (http://www.veloagenda.ch/Velophysik/luftwid.htm)
                $kSteigung = sin(atan($prozent / 100));
                $mHinterrad = ($fahrer->gewicht + 15) * 9.81 * ($kSteigung + 0.01) + (0.5 * $aFahrer) * 0.55 * 1.2 * (($fahrrad->geschwindigkeit / 3.6) * ($fahrrad->geschwindigkeit / 3.6));
                $mRad = (2.1 / (2 * pi())) * $mHinterrad;
                $mPed = $mRad;

                $fahrrad->sollDrehmoment = intval($mPed);

                $fahrrad->touch();
                $fahrrad->save();
            }
        }
    }

    /**
     * Gibt Informationen zur Strecke und deren zugehörige Abschnitte zurück
     *
     * Es wird folgender Input im Request erwartet:
     * * Strecke-ID
     *
     * @param Strecke $strecke
     *
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung:
     *  * { "strecke" : { "name": Streckenname, "abschnitte" : Abschnitte (JSON Array) } }
     */
    public function strecke(\App\Strecke $strecke)
    {
        return ["strecke" => [ "name" => $strecke->name, "abschnitte" => $strecke->abschnitte()]];
    }

    /**
     * Gibt Informationen zu allen Strecken in der Datenbank zurück.
     *
     * @return \Illuminate\Http\JsonResponse
     * * Rückmeldung:
     *  * Strecken (JSON Array)
     */
    public function strecken()
    {
        return Strecke::all();
    }

    /**
     * Gibt die zurückgelegte Strecke aller Fahrer zurück.
     *
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung (JSON Array):
     * * { "fahrerstrecke" : { "id" : Fahrer-ID, "name": Fahrer-Name, "strecke" : Zurückgelegte Strecke in Metern } }
     */
    public function fahrerstrecke(){
        $fahrraeder = Fahrrad::where("fahrer_id", "<>", null)->get();
        $result = [];

        foreach ($fahrraeder as $fahrrad){
            $result[] = [
                "id" => $fahrrad->getFahrerID(),
                "name" => $fahrrad->getFahrerName(),
                "strecke" => $fahrrad->strecke
            ];
        }

        return response()->json(["fahrerstrecke" => $result], 200);
    }

    /**
     * Gibt die momentan erzeugte Leistung aller Fahrer zurück.
     *
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung (JSON Array):
     * * { "fahrerleistung" : { "id" : Fahrer-ID, "name": Fahrer-Name, "istLeistung" : Erzeugte Leistung in Watt } }
     */
    public function leistung()
    {
        $fahrraeder = Fahrrad::where("fahrer_id", "<>", null)->get();
        $result = [];

        foreach ($fahrraeder as $fahrrad){
            $result[] = [
                "id" => $fahrrad->getFahrerID(),
                "name" => $fahrrad->getFahrerName(),
                "istLeistung" => $fahrrad->istLeistung
            ];
        }

        return response()->json(["fahrerleistung" => $result], 200);
    }

    /**
     * Gibt die aktuelle Gesamtstatistik aller Teilnehmer zurück:
     *
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung:
     * * { "statistik" : {"teilnehmer" : Anzahl Teilnehmer, "kilometer": Gesamtstrecke in Km, "hoehenmeter": Gesamthoehenmeter in Meter, "energie": Gesamtenergie (Durchschnitt) } }
     */
    public function statistik()
    {
        return response()->json([
            "statistik" => Statistik::getGesamtStatistik()
        ], 200);
    }

    /**
     * Statistik hinzufügen.
     *
     * Fügt einen Eintrag für alle aktiven Fahrer in die Statistik Tabelle ein, anhand der aktuell vorliegenden Daten
     */
    public function statistikUpdate()
    {
        $fahrraeder = Fahrrad::all();

        foreach($fahrraeder as $fahrrad){
            if($fahrrad->fahrer_id != null){
                $fahrer = Fahrer::whereId($fahrrad->fahrer_id)->first();
                Statistik::addEntry($fahrer, $fahrrad);
            }
        }
    }

    /**
     * Gibt die aktuellen Batteriespannung zurück.
     *
     * @return \Illuminate\Http\JsonResponse
     * Rückmeldung:
     * * { "batterie" : Spannung in Volt }
     */
    public function batterie()
    {
        return response()->json([
            "batterie" => Batterie::getCurrent()
        ], 200);
    }

    /**
     * Gibt die aktuelle Highscoreliste zurück.
     *
     * Die besten (bis zu) 3 Fahrer werden mit Namen und Durchschnittsleistung angegeben.
     *
     * @return array
     */
    public function highscore(){
        $highscoreListe = [];

        $statistiken = Statistik::all()->groupBy('vorgang');

        foreach ($statistiken as $statistik){
            $gesamtWatt = 0;
            foreach ($statistik as $s){
                $gesamtWatt += $s["attributes"]["gesamtleistung"];
            }

            $gesamtWh = (((($gesamtWatt / $statistik->count()) * $statistik->last()->fahrdauer) / 60));
            $gesamtWh = number_format((float)$gesamtWh, 2, '.', '');

            $fahrer_name = Fahrer::whereId($statistik[0]->fahrer_id)->first()->name;
            array_push($highscoreListe, [$fahrer_name, $gesamtWh]);
        }

        usort($highscoreListe, function($a, $b) {
            return $b[1] - $a[1];
        });

        $resultArray = [];

        if(isset($highscoreListe[0])){
            array_push($resultArray, $highscoreListe[0]);
        }
        if(isset($highscoreListe[1])){
            array_push($resultArray, $highscoreListe[1]);
        }
        if(isset($highscoreListe[2])){
            array_push($resultArray, $highscoreListe[2]);
        }

        return $resultArray;
    }
}
