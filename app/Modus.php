<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Modus
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Fahrer $fahrer
 * @property-read \App\Fahrrad $fahrrad
 * @method static \Illuminate\Database\Query\Builder|\App\Modus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modus whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Modus extends Model
{
    /**
     * @var string
     */
    protected $table = "modus";

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fahrer()
    {
        return $this->hasOne("App\Fahrer");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fahrrad()
    {
        return $this->hasOne("App\Fahrrad");
    }


    /**
     * @return mixed
     */
    public function getName(){
        return $this->name;
    }
}
