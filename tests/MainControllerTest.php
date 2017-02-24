<?php
/**
 * Hauptverantwortlich: Alice Domandl
 */

use App\Strecke;

/**
 * Created by PhpStorm.
 * User: Alice
 * Date: 01.02.2017
 * Time: 17:30
 */
class MainControllerTest extends TestCase
{

    public function testURL()
    {
        $response = $this->call('GET', 'central');
        $this->assertTrue($response->isOk());
    }

    public function testStrecken()
    {
        $this->json('GET', '/strecke')
            ->seeJsonEquals([
                Strecke::all()
            ]);
    }


}
