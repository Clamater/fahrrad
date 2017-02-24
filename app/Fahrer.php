<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Fahrer
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $gewicht
 * @property float $groesse
 * @property int $modus_id
 * @property string $vorgang
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Fahrrad $fahrrad
 * @property-read \App\Modus $modus
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Statistik[] $statistik
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereGewicht($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereGroesse($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereModusId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Fahrer whereVorgang($value)
 * @mixin \Eloquent
 */
class Fahrer extends Authenticatable
{
    use Notifiable;

    /**
     * @var string
     */
    protected $table = "fahrer";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'groesse', 'gewicht', 'vorgang'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fahrrad()
    {
        return $this->hasOne("App\Fahrrad");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statistik(){
        return $this->hasMany("App\Statistik");
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
}
