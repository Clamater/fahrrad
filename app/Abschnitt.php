<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Abschnitt
 *
 * @package App
 * @property int $id
 * @property int $strecke_id
 * @property int $hoehe
 * @property int $laenge
 * @method static \Illuminate\Database\Query\Builder|\App\Abschnitt whereHoehe($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Abschnitt whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Abschnitt whereLaenge($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Abschnitt whereStreckeId($value)
 * @mixin \Eloquent
 */
class Abschnitt extends Model
{
    /**
     * @var string
     */
    protected $table = "abschnitt";

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function strecke()
    {
        return $this->belongsTo("App\Strecke")->get();
    }

    /**
     * @return mixed
     */
    public function fahrraeder()
    {
        return $this->hasMany("App\Fahrrad")->get();
    }
}
