<?php

use App\Abschnitt;
use App\Fahrer;
use App\Fahrrad;
use App\Modus;
use App\Statistik;
use App\Strecke;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        #DB::statement('PRAGMA foreign_keys = OFF'); # Für Sqlite

        DB::table('fahrer')->truncate();
        Fahrer::create(['name' => 'Maik Braun', 'email' => 'MaikBraun@cuvox.de']);
        Fahrer::create(['name' => 'Petra Austerlitz']);
        Fahrer::create(['name' => 'Jonas Nussbaum', 'groesse' => 1.85, 'gewicht' => 85]);
        Fahrer::create(['name' => 'Juliane Seiler', 'email' => 'JulianeSeiler@cuvox.de', 'gewicht' => 87]);


        DB::table('strecke')->truncate();
        for($i = 1; $i <= 5; $i++){
            Strecke::create(['name' => 'Strecke '.$i]);
        }

        DB::table('modus')->truncate();
        Modus::create(['name' => 'Strecke']);
        Modus::create(['name' => 'Konstantes Drehmoment']);
        Modus::create(['name' => 'Konstante Leistung']);



        DB::table('abschnitt')->truncate();
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 3, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 3, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 6, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 6, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 9, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 1, 'hoehe' => 9, 'laenge' => 250]);

        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 3, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 6, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 9, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 9, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 12, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 2, 'hoehe' => 15, 'laenge' => 250]);

        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 3, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 6, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 9, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 9, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 12, 'laenge' => 250]);
        Abschnitt::create(['strecke_id' => 3, 'hoehe' => 12, 'laenge' => 250]);

        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 5, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 5, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 10, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 10, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 15, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 4, 'hoehe' => 15, 'laenge' => 500]);

        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 5, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 10, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 10, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 15, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 20, 'laenge' => 500]);
        Abschnitt::create(['strecke_id' => 5, 'hoehe' => 20, 'laenge' => 500]);



        DB::table('fahrrad')->truncate();
        $colors = ["#EC87C0", "#5D9CEC", "#FFCE54"]; // pink, blau , gelb
        Fahrrad::create(['ip' => '192.168.4.7', 'mac' => '60:01:94:06:38:C3', 'color' => $colors[0] ]);
        Fahrrad::create(['ip' => '192.168.4.4', 'mac' => '60:01:94:0E:C7:CE', 'color' => $colors[1] ]);
        Fahrrad::create(['ip' => '192.168.4.2', 'mac' => '60:01:94:0E:C9:8F', 'color' => $colors[2] ]);

        DB::table('statistik')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        #DB::statement('PRAGMA foreign_keys = ON'); # Für Sqlite
    }
}
