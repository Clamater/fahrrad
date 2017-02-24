<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Fahrer Routen
Route::get("fahrer/{fahrer}", "FahrerController@show");
Route::post("fahrer", "FahrerController@store");
Route::put("fahrer/{fahrer}", "FahrerController@update");
Route::delete("fahrer/{fahrer}", "FahrerController@destroy");
Route::get("allnames", "FahrerController@getAllNames");

// Fahrrad Routen
Route::put("fahrrad/{fahrrad}", "FahrradController@update");
Route::get("data", "FahrradController@getData");
Route::get("fahrrad/{fahrrad}/fahrer/{fahrer}", "FahrradController@zuordnungHerstellen");
Route::delete("fahrrad/{fahrrad}", "FahrradController@zuordnungLoeschen");

/*
|--------------------------------------------------------------------------
| Zentrale Ansicht
|
| Zeigt die Startseite mit Informationen an
|--------------------------------------------------------------------------
*/
Route::get("central", ["as" => "central", "uses" => "MainController@showCentral"]); /*  */
Route::get("/", function (){ return redirect("central"); });

/*
|--------------------------------------------------------------------------
| Administrative Ansicht
|--------------------------------------------------------------------------
*/

Route::get("admin/login", ["as" => "admin/login", "uses" => "AdminController@getLogin"]);
Route::post("admin/login", ["as" => "login", "uses" => "AdminController@login"]);

Route::group(['middleware' => \App\Http\Middleware\Admin::class], function () {
    Route::get("admin", ["as" => "admin", "uses" => "AdminController@index"]);
    Route::get("admin/logout", "AdminController@logout");
});

/*
|--------------------------------------------------------------------------
| Datenaustausch
|--------------------------------------------------------------------------
*/
Route::get("leistung", "MainController@leistung");
Route::post("abschnitt", "MainController@setAbschnitt");
Route::get("strecke", "MainController@strecken");
Route::get("strecke/{strecke}", "MainController@strecke");
Route::get("fahrerstrecke", "MainController@fahrerstrecke");

Route::get("statistik", "MainController@statistik");
Route::get("statistikupdate", "MainController@statistikUpdate");

Route::get("batterie", "MainController@batterie");
Route::get("highscore", "MainController@highscore");