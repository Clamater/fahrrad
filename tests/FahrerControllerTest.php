<?php
/**
 * Hauptverantwortlich: Alice Domandl
 */

use App\Fahrer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Http\Controllers\FahrerController;

/**
 * Created by PhpStorm.
 * User: Alice
 * Date: 05.02.2017
 * Time: 18:09
 */
class FahrerControllerTest extends TestCase
{

    public function testCreateFahrerSuccessful()
    {
        $this->json('POST', '/fahrer', ['name' => 'Sally'])
            ->seeJsonEquals([
                "msg" => "ok", "err" => 0, "fahrer" => Fahrer::whereName("Sally")->get(),
            ]);
    }

    public function testCreateFahrerFailed()
    {
        $this->call('POST', '/fahrer', ['name' => 'Sally']);
        $this->json('POST', '/fahrer', ['name' => 'Sally'])
            ->seeJsonEquals([
                "msg" => "Name schon vorhanden", "err" => 1,
            ]);
    }

    public function testShowFahrer()
    {
        $this->json('POST', '/fahrer', ['name' => 'Sally']);
        $this->seeInDatabase('fahrer', [
            'name' => 'Sally'
        ]);

    }

    public function testGetAllFahrer()
    {
        $this->call('POST', '/fahrer', ['name' => 'Sally']);
        $result = ["Jonas Nussbaum", "Juliane Seiler", "Maik Braun", "Petra Austerlitz", "Sally"];
        $this->json('GET', '/allnames')
            ->seeJsonEquals([
                "msg" => "ok", "names" => $result,
            ]);
    }

    public function testUpdateFahrer()
    {
        $fahrer = $this->json('POST', '/fahrer', ['id' => '5', 'name' => 'Sally']);
        $this->json('PUT', '/fahrer/5', ['id' => '5', 'email' => 'sally@gmail.com'])
            ->seeJsonEquals([Fahrer::whereId('5')->get()]);
    }

    public function testDeleteFahrer()
    {
        $fahrer = $this->json('POST', '/fahrer', ['id' => '5', 'name' => 'Sally']);
        $this->json('DELETE', '/fahrer/5')
            ->seeJsonEquals([
                "msg" => "ok", 'id' => []
            ]);
    }
}
