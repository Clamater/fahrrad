<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Fahrrad
 *
 * @package App
 * @property int $id
 * @property int $fahrer_id
 * @property string $ip
 * @property string $mac
 * @property int $geschwindigkeit
 * @property float $istLeistung
 * @property float $sollLeistung
 * @property float $sollDrehmoment
 * @property int $strecke
 * @property int $hoehenmeter
 * @property string $color
 * @property int $modus_id
 * @property int $abschnitt_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $zugeordnet_at
 * @property-read \App\Fahrer $fahrer
 * @property-read \App\Modus $modus
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereAbschnittId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereColor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereFahrerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereGeschwindigkeit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereHoehenmeter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereIp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereIstLeistung($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereMac($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereModusId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereSollDrehmoment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereSollLeistung($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereStrecke($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrrad whereZugeordnetAt($value)
 * @mixin \Eloquent
 */
class Fahrrad extends Model
{
    /**
     * @var string
     */
    protected $table = "fahrrad";
    /**
     * @var array
     */
    protected $touches = ['fahrer'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fahrer()
    {
        return $this->belongsTo("App\Fahrer");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modus()
    {
        return $this->belongsTo("App\Modus");
    }

    /**
     * @return mixed
     */
    public function modusName()
    {
        return Modus::whereId($this->modus_id)->pluck("name")->first();
    }


    /**
     * @return bool
     */
    public function aktiv()
    {
        return $this->fahrer_id != 0;
    }

    /**
     * @return string
     */
    public function getFahrerName()
    {
        $fahrer = Fahrer::where("id", $this->fahrer_id)->first();
        if($fahrer){
            return $fahrer->name;
        }

        return "-";
    }

    /**
     * @return int
     */
    public function getFahrerID()
    {
        $fahrer = Fahrer::where("id", $this->fahrer_id)->first();
        if($fahrer){
            return $fahrer->id;
        }

        return 0;
    }

    /**
     * @return mixed
     */
    public function abschnitt()
    {
        return $this->belongsTo("App\Abschnitt")->get();
    }


    /**
     *
     */
    public function resetData()
    {
        $this->abschnitt_id = null;
        $this->geschwindigkeit = 0;
        $this->istLeistung = 0;
        $this->sollLeistung = null;
        $this->sollDrehmoment = null;
        $this->strecke = 0;
        $this->hoehenmeter = 0;
    }
}
