<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Strecke
 *
 * @package App
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Query\Builder|\App\Strecke whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Strecke whereName($value)
 * @mixin \Eloquent
 */
class Strecke extends Model
{
    /**
     * @var string
     */
    protected $table = "strecke";

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function abschnitte()
    {
        return $this->hasMany("App\Abschnitt")->get();
    }
}
