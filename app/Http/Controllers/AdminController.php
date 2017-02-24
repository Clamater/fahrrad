<?php
/**
 * Hauptverantwortlich: Enrico Costanzo
 */

namespace App\Http\Controllers;

use App\Fahrer;
use App\Fahrrad;
use App\Modus;
use Illuminate\Http\Request;

/**
 * Class AdminController
 *
 * Diese Klasse verwaltet den Login/Logout des Adminbereiches.
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Admin Passwort.
     *
     * Das Passwort wird für den Zugang zum Adminbereich benötigt.
     *
     * @var string
     */
    protected $admin_password = "test";

    /**
     * Zeigt den Adminbereich an.
     *
     * Es werden die folgenden Datensätze übergeben:
     * * Alle Fahrer
     * * Alle Modi
     * * Alle Fahrräder
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index')
            ->with("fahrer", Fahrer::all())
            ->with("modi", Modus::all())
            ->with("fahrraeder", Fahrrad::all());
    }

    /**
     * Zeigt die Loginseite zum Adminbereich.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogin()
    {
        return view("admin.login");
    }

    /**
     * Verarbeitet den Login Request.
     *
     * Erfolg:
     * * Der Benutzer wird in den Adminbereich weitergeleitet
     * * Ein Cookie wird gesetzt
     *
     * Fehler:
     * * Der Benutzer bleibt auf der Seite und bekommt eine Fehlermeldung angezeigt
     *
     * @param Request $request
     * @return Redirect(Admin)
     */
    public function login(Request $request)
    {
        if($request->password == $this->admin_password){
            return redirect("admin")->cookie('admin', '1', 3600);
        }
        return redirect("admin/login")
            ->withErrors(["password" => ('Falsches Passwort!')]);
    }

    /**
     * Meldet den Benutzer aus dem Adminbereich ab.
     *
     * * Der Cookie wird gelöscht.
     *
     * @return $this
     */
    public function logout()
    {
        return redirect("admin")->cookie('admin', '1', -1);
    }
}
