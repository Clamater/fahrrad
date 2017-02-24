<?php
/**
 * Hauptverantwortlich: Alice Domandl
 */

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

/**
 * Created by PhpStorm.
 * User: Alice
 * Date: 02.02.2017
 * Time: 14:56
 */
class AdminLoginLogoutControllerTest extends TestCase
{

    public function testURL()
    {
        $response = $this->call('GET', 'admin/login');
        $this->assertEquals(200, $response->status());
    }

    public function testGetLogin()
    {
        $this->visitRoute('admin/login');
    }

    public function testBlankLogin()
    {
        $this->visitRoute('admin/login');
        $this->press('btnLogin');
        $this->seePageIs('admin/login');
        $this->see("Falsches Passwort");

    }

//    public function testLoginSuccessful()
//    {
//        $cookie = ["key" => "admin", "id" => "1"];
//        // Visit Seite bzw. Get admin/login
//        $this->visitRoute('login');
//        // type password
//        $this->type("test", "password");
//        // press Login Button
//        $this->press("Login");
//        // Request / setcookie / checkPasswort
//        $this->call('POST', "login", $cookie);
//        // gehe Zur Seite admin
//        $this->seePageIs("admin");
//        // prüfe ob admin
//
//    }

//    public function testLoginFailed()
//    {
//        // Visit Seite bzw. Get admin/login
//        $this->visitRoute("login");
//        // type falschespasswort
//        $this->type("falsch", "password");
//        // press Login Button
//        $this->press("Login");
//        // redirect admin/login
//        // prüfe ob admin/login
//        $this->seePageIs("admin/login");
//        // prüfe ob Fehler angezeigt
//        $this->see("Falsches Passwort");
//
//    }

//    public function testLogout()
//    {
//        // Visit seite bzw. geh auf admin
//        $this->visitRoute("admin");
//        // press Logout Button
////        $this->press("btnLogout");
//        // Redirect admin/login
////        $this->seePageIs("login");
//        // prüfe ob admin/login
//    }
}
