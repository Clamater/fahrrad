<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Batterie
 *
 * @package App
 * @property int $id
 * @property float $spannung
 * @property float $generatorstrom
 * @property float $laststrom
 * @method static \Illuminate\Database\Query\Builder|\App\Batterie whereGeneratorstrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Batterie whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Batterie whereLaststrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Batterie whereSpannung($value)
 * @mixin \Eloquent
 */
class Batterie extends Model
{
    /**
     * @var string
     */
    protected $table = "batterie";

    /**
     * @return mixed
     */
    public static function getCurrent(){
        $batterie = Batterie::all()->last();
        return $batterie;
    }
}
