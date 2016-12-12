<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStructureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        #DB::statement('PRAGMA foreign_keys = OFF');

        Schema::create('fahrer', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->integer('gewicht')->default(80);
            $table->float('groesse')->default(1.8);

            $table->integer("modus_id")->unsigned()->default(1);
            $table->foreign("modus_id")->references("id")->on("modus");

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('strecke', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
        });

        Schema::create('modus', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name");
            $table->timestamps();
        });

        Schema::create('abschnitt', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("strecke_id")->unsigned();
            $table->foreign("strecke_id")->references("id")->on("strecke");

            $table->integer("hoehe")->default(100);
            $table->integer("laenge")->default(100);
        });

        Schema::create('fahrrad', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("fahrer_id")->unsigned()->nullable();
            $table->foreign("fahrer_id")->references("id")->on("fahrer")->onDelete('set null');

            $table->string("ip")->default("127.0.0.1");
            $table->string("mac")->default("00:00:00:00:00:00");

            $table->integer("geschwindigkeit")->default(0);
            $table->float("istLeistung")->default(0.0);

            $table->float("sollLeistung")->nullable();
            $table->float("sollDrehmoment")->nullable();

            $table->integer("strecke")->default(0)->nullable();

            $table->integer("strecke_id")->unsigned()->nullable();
            $table->foreign("strecke_id")->references("id")->on("strecke");

            $table->integer("abschnitt_id")->unsigned()->nullable();
            $table->foreign("abschnitt_id")->references("id")->on("abschnitt");

            $table->integer("modus_id")->unsigned()->default(1);
            $table->foreign("modus_id")->references("id")->on("modus");

            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        #DB::statement('PRAGMA foreign_keys = ON');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        #DB::statement('PRAGMA foreign_keys = OFF');

        Schema::dropIfExists('abschnitt');
        Schema::dropIfExists('strecke');
        Schema::dropIfExists('modus');
        Schema::dropIfExists('fahrrad');
        Schema::dropIfExists('fahrer');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        #DB::statement('PRAGMA foreign_keys = ON');
    }
}
