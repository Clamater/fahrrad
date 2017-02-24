<?php
/**
 * Hauptverantwortlich: Alice Domandl
 */

use App\Fahrrad;
use App\Fahrer;
use App\Modus;
use App\Statistik;
use App\StatistikMail;

/**
 * Created by PhpStorm.
 * User: Alice
 * Date: 06.02.2017
 * Time: 23:08
 */
class FahrradControllerTest extends TestCase
{

    public function testUpdate()
    {
        $this->json('PUT', '/fahrrad/2', ['id' => '2', 'modus' => '1', 'fahrer_id' => '1'])
            ->seeJsonEquals([Fahrrad::whereId('2')->first()]);
    }

    public function testZuordnungHerstellenSuccessful()
    {
        $this->json('GET', '/fahrrad/1/fahrer/2', ['fahrrad_id' => '1', 'fahrer_id' => '2'])
            ->seeJsonEquals(["fahrrad" => Fahrrad::whereId('1')->get(),
                "fahrer" => Fahrer::whereId('2')->get(),
                "modi" => Modus::all()]);

    }

    public function testZuordnungHerstellenFahradNichtGefunden()
    {
        $this->json('GET', '/fahrrad/4/fahrer/2', ['fahrrad_id' => '4', 'fahrer_id' => '2'])
            ->seeJsonEquals(["msg" => "Fahrrad nicht gefunden", "err" => 3]);

    }

    public function testZuordnungHerstellenFahrerNichtGefunden()
    {
        $this->json('GET', '/fahrrad/1/fahrer/1000', ['fahrrad_id' => '1', 'fahrer_id' => '1000'])
            ->seeJsonEquals(["msg" => "Fahrer nicht gefunden", "err" => 4]);

    }

    public function testZuordnungHerstellenFahrerSchonZugeordnet()
    {
        $this->json('GET', '/fahrrad/1/fahrer/1', ['fahrrad_id' => '1', 'fahrer_id' => '1']);
        $this->json('GET', '/fahrrad/1/fahrer/1', ['fahrrad_id' => '1', 'fahrer_id' => '1'])
            ->seeJsonEquals(["msg" => "Fahrer schon zugeordnet", "err" => 1]);

    }

    public function testZuordnungHerstellenFahrradBesetzt()
    {
        $this->json('GET', '/fahrrad/1/fahrer/1', ['fahrrad_id' => '1', 'fahrer_id' => '1']);
        $this->json('GET', '/fahrrad/1/fahrer/2', ['fahrrad_id' => '1', 'fahrer_id' => '2'])
            ->seeJsonEquals(["msg" => "Fahrrad ist besetzt", "err" => 2]);

    }


    public function testZuordnungLoeschenOhneMailSuccessful()
    {
        $this->json('GET', '/fahrrad/1/fahrer/2', ['fahrrad_id' => '1', 'fahrer_id' => '2']);
        $this->json('DELETE', '/fahrrad/1')
            ->seeJsonEquals([
                "msg" => "ok", "email" => false
            ]);
    }

//    public function testZuordnungLoeschenMitMailSuccessful()
//    {
//        $this->json('POST', '/fahrer', ['name' => 'Sally', 'id' => '5', 'email' => 'SPIN.201617@gmail.com']);
//        $statistik = Statistik::create(['id' => '1', 'fahrer_id' => '5', 'modus_id' => '1',
//            'vorgang' => '2']);
//        $this->json('GET', '/fahrrad/1/fahrer/5', ['fahrrad_id' => '1', 'fahrer_id' => '1']);
//        $this->json('GET', '/statistikupdate', [$statistik]);
//        $this->json('DELETE', '/fahrrad/1')
//            ->seeJsonEquals([
//                "msg" => "ok", "email" => true
//            ]);
//    }

    public function testZuordnungLoeschenFailed()
    {
        $this->json('DELETE', '/fahrrad/3')
            ->seeJsonEquals([
                "msg" => "Error"
            ]);
    }

    public function testGetData()
    {
        $this->json('GET', '/fahrrad/1/fahrer/2', ['fahrrad_id' => '1', 'fahrer_id' => '2', 'modus' => '1']);
        $this->json('GET', '/data')
            ->seeJsonEquals([
                "data" => ["fahrrad" => Fahrrad::with("modus")->with("fahrer")->get()]
            ]);
    }
}
