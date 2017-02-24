<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 * Zuarbeit:
 *      Alice Domandl
 *          sendMail() Methode
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.2017
 * Time: 01:33
 */

namespace App;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Swift_Mailer;
use Swift_SmtpTransport;

/**
 * Class StatistikMail
 * @package App
 */
class StatistikMail
{

    /**
     * @param Fahrer $fahrer
     * @return array|null
     */
    public static function getData(\App\Fahrer $fahrer){
        if($fahrer){
            $fahrer_statistiken = Statistik::whereFahrerId($fahrer->id)->whereVorgang($fahrer->vorgang)->orderBy("created_at", "DESC")->get();

            if(!$fahrer_statistiken->isEmpty()){
                $datum = explode(" ", $fahrer_statistiken[0]["created_at"])[0];
                $datum_formatiert = explode("-", $datum)[2].".".explode("-", $datum)[1].".".explode("-", $datum)[0];

                $gesamtStrecke  = $fahrer_statistiken[0]["strecke"];

                $modus = Modus::whereId($fahrer_statistiken[0]["modus_id"])->first()->name;
                $hoehenmeter    = $fahrer_statistiken[0]["hoehenmeter"];
                $fahrdauer      = $fahrer_statistiken[0]["fahrdauer"];

                $gesamtLeistung = 0;
                $gesamtGeschwindigkeit = 0;
                $hoechstGeschwindigkeit = 0;
                foreach ($fahrer_statistiken as $fahrer_statistik){
                    $gesamtLeistung += $fahrer_statistik["gesamtleistung"];
                    $gesamtGeschwindigkeit += $fahrer_statistik["geschwindigkeit"];

                    if($fahrer_statistik["geschwindigkeit"] > $hoechstGeschwindigkeit){
                        $hoechstGeschwindigkeit = $fahrer_statistik["geschwindigkeit"];
                    }
                }
                $durchschnittsGeschwindigkeit   = $gesamtGeschwindigkeit / count($fahrer_statistiken);
                $durchschnittsLeistung          = $gesamtLeistung / count($fahrer_statistiken);

                $data = [
                    'name'                          => $fahrer->name,
                    'modus'                         => $modus,
                    'hoechstGeschwindigkeit'        => $hoechstGeschwindigkeit,
                    'durchschnittsGeschwindigkeit'  => $durchschnittsGeschwindigkeit,
                    'gesamtleistung'                => $gesamtLeistung,
                    'durchschnittsLeistung'         => $durchschnittsLeistung,
                    'strecke'                       => $gesamtStrecke,
                    'hoehenmeter'                   => $hoehenmeter,
                    'fahrdauer'                     => $fahrdauer,
                    'datum'                         => $datum_formatiert
                ];

                return $data;
            }else{
                return null;
            }
        }
    }

    /**
     * @param Fahrer $fahrer
     * @return bool
     */
    public static function sendMail(\App\Fahrer $fahrer){
        $data = StatistikMail::getData($fahrer);
        if($data){
            $success = true;

            $https['ssl']['verify_peer'] = FALSE;
            $https['ssl']['verify_peer_name'] = FALSE;

            // Send email notification
            $transport = Swift_SmtpTransport::newInstance(
                Config::get('mail.host'),
                Config::get('mail.port'),
                Config::get('mail.encryption')
            )
                ->setUsername(Config::get('mail.username'))
                ->setPassword(Config::get('mail.password'))
                ->setStreamOptions($https);

            $mailer = Swift_Mailer::newInstance($transport);
            Mail::setSwiftMailer($mailer);



            Mail::send('mail.mail', $data, function (\Illuminate\Mail\Message $message) use ($fahrer) {
                $message->subject('HIT 2017');
                $message->from('SPIN.201617@gmail.com', 'W-HS HIT SPIN 2017');
                $message->to($fahrer->email, $fahrer->name);
            });

            $errors = Mail::failures();
            if(count($errors) > 0) {
                $success = false;
            }

            return $success;
        }

        return false;
    }
}