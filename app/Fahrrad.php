<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fahrrad extends Model
{
    protected $table = "fahrrad";

    protected $touches = ['fahrer'];

    public function fahrer()
    {
        return $this->belongsTo("App\Fahrer");
    }

    public function modus()
    {
        return $this->belongsTo("App\Modus");
    }

    public function modusName()
    {
        return Modus::whereId($this->modus_id)->pluck("name")->first();
    }


    public function aktiv()
    {
        return $this->fahrer_id != 0;
    }

    public function getFahrerName()
    {
        // Todo: Beziehung this->fahrer() nutzen
        $fahrer = Fahrer::where("id", $this->fahrer_id)->first();
        if($fahrer){
            return $fahrer->name;
        }

        return "-";
    }

    public function getFahrerID()
    {
        $fahrer = Fahrer::where("id", $this->fahrer_id)->first();
        if($fahrer){
            return $fahrer->id;
        }

        return 0;
    }

    public function abschnitt()
    {
        return $this->belongsTo("App\Abschnitt")->get();
    }
}
