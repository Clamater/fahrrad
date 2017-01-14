<?php

namespace App\Http\Controllers;

use App\Abschnitt;
use App\Fahrer;
use App\Fahrrad;
use App\Statistik;
use App\Strecke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCentral()
    {
        return view('central.index')->with("fahrraeder", Fahrrad::all());
    }

    public function setBatteryData(Request $request)
    {

    }

    // Fahrer wechselt den Streckenabschnitt
    // Update von fahrrad.abschnitt_id / fahrrad.sollDrehmoment (abhängig von fahrer.gewicht und fahrer.goresse)
    public function setAbschnitt(Request $request)
    {
        $this->validate($request, [
            "fahrrad_id" => "required",
            "abschnitt_id" => "required"
        ]);

        $fahrrad = Fahrrad::whereId(Input::get("fahrrad_id"))->first();
        if($fahrrad){
            $fahrer = Fahrer::whereId($fahrrad->fahrer_id)->first();

            $abschnitt_id      = Input::get("abschnitt_id");
            $abschnitt         = Abschnitt::whereId($abschnitt_id)->first();
            $abschnitt_folgend = Abschnitt::whereId($abschnitt_id+1)->first();

            if(!empty($abschnitt) && !empty($abschnitt_folgend)){
                $fahrrad->abschnitt_id = $abschnitt->id;

                // Berechnen von sollDrehmoment
                $h = $abschnitt_folgend->hoehe - $abschnitt->hoehe;
                $l = $abschnitt->laenge;

                // Steigung des Abschnitts in Prozent
                $prozent = intval($h / $l * 100);

                $aFahrer = $fahrer->groesse * 0.28; // 0.28 Korrekturfaktor (http://www.veloagenda.ch/Velophysik/luftwid.htm)
                $kSteigung = sin(atan($prozent / 100));
                $mHinterrad = ($fahrer->gewicht + 15) * 9.81 * ($kSteigung + 0.01) + (0.5 * $aFahrer) * 0.55 * 1.2 * ($fahrrad->geschwindigkeit * 3.6);
                $mRad = 2.1 / ((2 * pi()) * $mHinterrad);
                $mPed = abs(intval($mRad * 10000));

                $fahrrad->sollDrehmoment = $mPed;

                $fahrrad->touch();
                $fahrrad->save();
            }
        }
    }

    public function strecke(\App\Strecke $strecke)
    {
        return ["strecke" => [ "name" => $strecke->name, "abschnitte" => $strecke->abschnitte()]];
    }

    public function strecken()
    {
        return Strecke::all();
    }

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

    public function statistik()
    {
        Statistik::addTeilnehmer();
        Statistik::addKilometer(20);
        Statistik::addHoehenmeter(20);
        Statistik::addEnergie(20);

        return response()->json([
            "statistik" => Statistik::get()
        ], 200);
    }
}
