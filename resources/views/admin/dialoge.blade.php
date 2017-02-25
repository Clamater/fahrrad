<!-- Hauptverantwortlich: Vanessa Ferrarello & Clara Terbeck

        Clara Terbeck:
            - Benutzerdokumentation
            - Overlay Fahrer hinzufügen

        Vanessa Ferrarello:
            - Benutzerdokumentation - Einstellungen
            - Fehler- und Warnmeldungen
            - Overlay Einstellungen
-->

<!-- #################################### Benutzerdokumentation #####################################################-->

<!-- Benutzerdokumentation Fahrer hinzufügen -->
<div id="hilfeFahrer" title="Hilfe" style="display:none;">
    <h3><b>Fahrer hinzufügen</b></h3><hr/>
    <p>Um einen Fahrer hinzuzufügen muss das Formular ausgefüllt werden:</p><br>
    <ul>
        <li><u>Name:</u> Hier muss ein Name für den Fahrer angegeben werden. Wenn der Fahrer nicht seinen eigenen
        angeben möchte, besteht die Möglichkeit über den Button "Zufall" einen alternativen Namen zu benutzen.</li>
        <li><u>Email-Adresse:</u> Hier kann der Fahrer seine Email-Adresse angeben, damit ihm seine Statistik per
        Email zugesendet werden kann. Diese Angabe ist optional.</li>
        <li><u>Wunschbetriebsmodus:</u> Hier kann der Fahrer sich einen Betriebsmodus auswählen, den er fahren möchte
        sobald er einem Fahrrad zugeordnet wird. Dieser kann jedoch bei der Fahrt auch noch verändert werden. Der
        Fahrer kann aus ingesamt drei verschiedenen Betriebsmodi auswählen:
            <ol>
                <li><b>Streckenmodus:</b> In diesem Modus fährt der Fahrer eine virtuelle Strecke ab. Die Strecke ist
                    in Abschnitte gegliedert. Jeder Abschnitt hat eine andere Steigung. So hat der Fahrer das Gefühl, dass
                    er mal bergauf und mal bergab fährt. Um es möglichst realistisch wirken zu lassen, wird anhand der Größe
                    und des Gewichtes des Fahreres noch zusätzlich der Windwiderstand berechnet.</li>
                <li><b>Konstante Leistung:</b> In diesem Betriebsmodus erbringt der Fahrer eine konstante Leistung. Um
                    dies zu Erreichen wird die Gegenkraft automatisch geregelt.<br>Tritt der Fahrer langsam, ist die
                    Gegenkraft entsprechend hoch und das Treten fällt dem Fahrer schwer.<br>Tritt der Fahrer hingegen schnell
                    , dann ist die Gegenkraft geringer und das Treten somit leichter.</li>
                <li><b>Konstantes Drehmoment:</b> In diesem Betriebsmodus ist die Gegenkraft immer gleich. Je nachdem
                    wie schnell der Fahrer tritt, erzeugt er eine höhere oder niedrigere Leistung. Das Treten fällt ihm
                    jedoch immer gleich schwer bzw. leicht. </li>
            </ol>
        </li>
        <li><u>Größe:</u> Hier kann der Fahrer seine Größe angeben, damit der passende Windwiderstand berechnet werden
        kann. Möchte er dies nicht, wird ein Standardwert von 1,8m benutzt.</li>
        <li><u>Gewicht:</u> Hier kann der Fahrer sein Gewicht angeben, damit das Fahrrad dementsprechend angepasst werden
        kann. Möchte er dies nicht, wird ein Standardwert von 80kg benutzt.</li>
    </ul>
</div>

<!-- Benutzerdokumentation Tabelle -->
<div id="hilfeTabelle" title="Hilfe" style="display: none;">
    <h3><b>Fahrertabelle</b></h3><hr/>
    <p>In dieser Tabelle werden alle bereits registrierten Fahrer angezeigt.<br>In den Spalten sind folgende
    Funktionen oder Daten dargestellt:</p>
    <ol>
        <li><u>Button zum Markieren der jeweiligen Tabellenzeile mit den Fahrerdaten:</u> Anschließend
        kann der Fahrer einem Fahrrad zugeordnet werden.</li>
        <li><u>Name:</u> Name des jeweiligen Fahrers</li>
        <li><u>Email:</u> Email-Adresse des jeweiligen Fahrers</li>
        <li><u>Gewicht:</u> Gewicht des jeweiligen Fahrers</li>
        <li><u>Größe:</u> Größe des jeweiligen Fahrers</li>
        <li><u>Betriebsmodus:</u> Hier ist der gewünschte Betriebsmodus des Fahrers eingetragen. Dieser kann
            über die Dropdown-Liste geändert werden und wird bei der Zuordnung automatisch übernommen.</li>
        <li><u>Button zur Löschung des gesamten Fahrerdatensatzes:</u> Dieser wird somit aus der Datenbank entfernt
        und kann auch nicht wiederhergestellt werden.</li>
    </ol>

    <p>Um die Spalten 2, 3, 4 und 5 zu bearbeiten muss lediglich in das zu bearbeitende Feld geklickt werden. Dann können
        die neuen Daten eingegeben werden und sobald man wieder außerhalb des Feldes klickt wird die Änderung
        automatisch gespeichert.<br><p>

    <p>Mithilfe des Buttons "Fahrer hinzufügen" kann ein neuer Datensatz erstellt werden.<br></p>

    Mit der Suchfunktion kann nach dem Datensatz eines bestimmten Fahrers gesucht werden. Dazu muss lediglich
    der Name des gesuchten Fahrers in das Suchfeld eingegeben werden.</p>
</div>

<!-- Benutzerdokumentation inaktives Fahrrad -->
<div id="hilfeInaktiv" title="Hilfe" style="display: none;">
    <h3><b>Fahrrad ist inaktiv</b></h3><hr/>
    <p>Dieses Fahrrad wird aktuell nicht benutzt und ist somit inaktiv.<br>
    Um einen Fahrer zuzuordnen muss er zunächst in der Tabelle rechts über den Button in der ersten Spalte markiert
        werden. Daraufhin muss der Button "Zuordnung hinzufügen" geklickt werden.<br>
        Nach dem Zuordnen wird das Fahrrad als aktiv gekennzeichnet, indem die Fahrerinformationen
        angezeigt werden.</p>
</div>

<!-- Benutzerdokumentation zugeordnetes Fahrrad -->
<div id="hilfeAktiv" title="Hilfe" style="display: none;">
    <h3><b>Fahrrad ist aktiv</b></h3><hr/>
    <p>Diesem Fahrrad ist ein Fahrer zugeordnet und es wird somit aktiv benutzt.<br>Es werden
        folgende Daten angezeigt:</p>
    <ul>
        <li><b><u>Fahrer:</u></b> Der Name des Fahrers</li>
        <li><b><u>Geschwindigkeit:</u></b> Die aktuelle Geschwindigkeit in Kilometern pro Stunde</li>
        <li><b><u>Gesamtleistung:</u></b> Die Leistung, die der Fahrer ingesamt erzeugt hat, in Kilowatt pro Stunde</li>
        <li><b><u>Zurückgelegte Strecke:</u></b> Die gefahrene Strecke in Kilometern</li>
        <li><b><u>Fahrdauer:</u></b> Die Zeit, die der Fahrer bereits fährt, in Minuten</li>
        <li><b><u>Betriebsmodus:</u></b> Es stehen drei verschiedene Betriebsmodi zur Auswahl,
            die über die Dropdown-Liste eingestellt werden können:
            <ol>
                <li><b>Streckenmodus:</b> In diesem Modus fährt der Fahrer eine virtuelle Strecke ab. Die Strecke ist
                in Abschnitte gegliedert. Jeder Abschnitt hat eine andere Steigung. So hat der Fahrer das Gefühl, dass
                er mal bergauf und mal bergab fährt. Um es möglichst realistisch wirken zu lassen, wird anhand der Größe
                und des Gewichtes des Fahreres noch zusätzlich der Windwiderstand berechnet.</li>
                <li><b>Konstante Leistung:</b> In diesem Betriebsmodus erbringt der Fahrer eine konstante Leistung. Um
                dies zu Erreichen wird die Gegenkraft automatisch geregelt.<br>Tritt der Fahrer langsam, ist die
                Gegenkraft entsprechend hoch und das Treten fällt dem Fahrer schwer.<br>Tritt der Fahrer hingegen schnell
                , dann ist die Gegenkraft geringer und das Treten somit leichter.</li>
                <li><b>Konstantes Drehmoment:</b> In diesem Betriebsmodus ist die Gegenkraft immer gleich. Je nachdem
                wie schnell der Fahrer tritt, erzeugt er eine höhere oder niedrigere Leistung. Das Treten fällt ihm
                jedoch immer gleich schwer bzw. leicht. </li>
            </ol>
        </li>
    </ul>
    <p>Über den Button "Zuordnung löschen" wird die Zuordnung vom Fahrer zum Fahrrad gelöscht und dieses wird
        als inaktiv gekennzeichnet.</p>
</div>

<!-- Benutzerdokumentation Einstellungen -->
<div id="hilfeEinstellungen" title="Hilfe" style="display: none;">
    <h3><b>Einstellungen</b></h3><hr/>
    <p>Es können folgende Einstellungen vorgenommen werden:</p>
    <ul>
        <li><b><u>Allgemein</u></b></li>
        <lu><ul><li><b><u>Individualmodus vs Gruppenmodus</u></b><br>Hier kann ausgewählt werden,
                    ob der Individualmodus oder der Gruppenmodus betrieben wird:
                    Im <b>Individualmodus</b> kann jeder Fahrer seinen individuellen Betriebsmodus wählen.
                    Im <b>Gruppenmodus</b> wird ein Betriebsmodus ausgewählt, der für alle Fahrer gilt. </li></ul></lu>
        <li><b><u>Strecke:</u></b><br>Hier kann die Auswahl der Strecke vorgenommen werden.<br>
            Es steht, je nach Schweregrad ("leicht", "mittel", "schwer"), eine Strecke zur Auswahl.</li>
    </ul>
    <p>Des Weiteren beinhaltet der Reiter <b><u>Info</u></b> Informationen über die Anwendung.</p>
</div>

<!-- #################################### Fehler- und Warnmeldungen ################################################-->
<div id="dialogValidationFailed" title="Fehler" style="display: none;">
    <h3><b>Ungültige Eingaben</b></h3><hr/>
    <p>Der Fahrer kann nicht hinzugefügt werden. Bitte überprüfen Sie die Eingaben und versuchen Sie es erneut.</p>
</div>

<div id="dialogKeinFahrerAusgewaehlt" title="Fehler" style="display: none;">
    <h3><b>Kein Fahrer ausgewählt</b></h3><hr/>
    <p>Sie müssen erst einen Fahrer auswählen, der einem Fahrrad zugeordnet werden soll.</p>
</div>

<div id="dialogFahrernameSchonVergeben" title="Fehler" style="display: none;">
    <h3><b>Name schon vergeben</b></h3><hr/>
    <p>Der angebene Name ist schon vergeben. Bitte wähle einen anderen Namen.</p>
    <p></p>
</div>

<div id="dialogFahrerSchonZugeordnet" title="Fehler" style="display: none;">
    <h3><b>Fahrer schon zugeordnet</b></h3><hr/>
    <p>Der ausgewählte Fahrer ist schon einem Fahrrad zugeordnet. Bitte wählen Sie einen anderen Fahrer aus, der diesem Fahrrad zugeordnet werden soll.</p>
</div>

<div id="dialogFahrradIstBesetzt" title="Fehler" style="display: none;">
    <h3><b>Fahrrad ist besetzt</b></h3><hr/>
    <p>Das ausgewählte Fahrrad wird gerade von einem anderen Fahrer benutzt. Bitte wählen Sie ein anderes Fahrrad aus.</p>
</div>

{{--
<div id="dialogFahrerNichtGefunden" title="Fehler" style="display: none;">
    <h3><b>Fahrer nicht gefunden.</b></h3><hr/>
    <p>Ein Fahrer mit dem eingegebenen Namen existiert nicht. Möchten Sie einen neuen Fahrer mit dem eingegebenen Namen hinzufügen?</p>
</div>
--}}

<div id="dialogZuordnungLoeschen" title="Warnung" style="display: none;">
    <h3><b>Zuordnung löschen</b></h3><hr/>
    <p>Die Zuordnung vom Fahrer zum Fahrrad wird gelöscht. Möchten Sie die Zuordnung wirklich löschen?</p>
</div>

<div id="dialogFahrerLoeschen" title="Warnung" style="display: none;">
    <h3><b>Fahrer löschen</b></h3><hr/>
    <p>Der Fahrer wird unwiderruflich aus der Datenbank gelöscht. Möchten Sie den Fahrer wirklich löschen?</p>
</div>

<div id="dialogEinstellungenSpeichern" title="Warnung" style="display: none;">
    <h3><b>Einstellungen Speichern</b></h3><hr/>
    <p>Sollen die Einstellungen wirklich gespeichert werden?</p>
</div>

<!-- #################################### Overlay Fahrer hinzufügen ################################################-->
<div id="addFahrer" title="Fahrer hinzufügen" style="display: none;">
    <div>
        <br>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('fahrer') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} col-md-12">
                <label for="name" class="col-md-4 control-label">Name</label>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-8 pull-left validation-error-wrapper">
                            <div id="validation-error-name" class="validation-error-msg "></div>
                            <input id="fahrername" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                        </div>
                        <div class="col-xs-4 pull-left">
                            <input id="btnGenerateName" type="button" class="form-control btn-success" value="Zufall" autofocus>
                        </div>
                    </div>
                    @if (isset($err_name))
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <small>{{ $err_name }}</small>
                            </div>
                        </div>
                    @endif
                    <div class="clear"></div>
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} col-md-12 text-nowrap">
                <label for="email" class="col-md-4 control-label">E-Mail Addresse <small>(optional)</small></label>

                <div class="col-md-6 validation-error-wrapper">
                    <div id ="validation-error-email" class="validation-error-msg"></div>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="col-md-4 control-label">Wunschbetriebsmodus</label>
                <div class="col-md-6">
                    <div class="radio"><label><input type="radio" name="betriebsmodus" value="1" checked>Streckenmodus</label></div>
                    <div class="radio"><label><input type="radio" name="betriebsmodus" value="2">Konstantes Drehmoment</label></div>
                    <div class="radio"><label><input type="radio" name="betriebsmodus" value="3">Konstante Leistung</label></div>
                </div>
            </div>

            <div class="form-group{{ $errors->has('groesse') ? ' has-error' : '' }} col-md-12">
                <label for="groesse" class="col-md-4 control-label">Gr&ouml;&szlig;e <small>(optional)</small></label>

                <div class="col-md-6 validation-error-wrapper">
                    <div id="validation-error-groesse" class="validation-error-msg"></div>
                    <input id="groesse" type="text" class="form-control" name="groesse" value="{{ old('groesse') }}" placeholder="1.80" autofocus>

                    @if ($errors->has('groesse'))
                        <span class="help-block">
                            <strong>{{ $errors->first('groesse') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group {{ $errors->has('gewicht') ? 'has-error' : '' }} col-md-12">
                <label for="gewicht" class="col-md-4 control-label">Gewicht <small>(optional)</small></label>

                <div class="col-md-6 validation-error-wrapper">
                    <div id="validation-error-gewicht" class="validation-error-msg"></div>
                    <input id="gewicht" type="text" class="form-control" name="gewicht" value="{{ old('gewicht') }}" placeholder="80" autofocus>

                    @if ($errors->has('gewicht'))
                        <span class="help-block">
                            <strong>{{ $errors->first('gewicht') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('groesse') ? ' has-error' : '' }} col-md-12">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" id="btnSubmitAddFahrer">Hinzufügen</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default" id="btnHilfeFahrer">Hilfe</button>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ##################################### Overlay Einstellungen ####################################################-->
<div id="dialogEinstellungen" title="Einstellungen" style="display: none;">
    <div class="einstellungen-wrapper">
        <form class="form-horizontal" role="form" method="POST" action="">

            <!-- Nav-Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tabAllgemein" role="tab" data-toggle="tab">Allgemein</a></li>
                <li role="presentation"><a href="#tabStrecke" role="tab" data-toggle="tab">Strecke</a></li>
                <li role="presentation"><a href="#tabInfo" role="tab" data-toggle="tab">Info</a></li>
            </ul>

            <!-- Tab-Inhalte -->
            <div class="tab-content">
                <!-- Allgemeine Einstellungen -->
                <div role="tabpanel" class="tab-pane tab-wrapper active" id="tabAllgemein">
                    <div id="einstellungen-modus col-md-12" title="Funktion nicht implementiert">
                        <div class="col-md-4">
                        <label for="streckenauswahl">Individualmodus vs. Gruppenmodus</label>
                        <fieldset name="streckenauswahl" disabled>
                            <div class="radio">
                                <label><input type="radio" name="radio-individualmodus" class="radio-modus" value="1" checked>Individualmodus</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="radio-gruppenmodus" class="radio-modus" value="2">Gruppenmodus</label>
                            </div>
                        </fieldset>
                        <div class="einstellungen-betriebsmodus" style="display: none">
                            <label for="betriebsmodus">Betriebsmodus</label>
                            <fieldset name="betriebsmodus" disabled>
                                <div class="radio">
                                    <label><input type="radio" name="radio-betriebsmodus" class="radio-betriebsmodus" value="1" checked>Streckenmodus</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="radio-betriebsmodus" class="radio-betriebsmodus"value="2">Konstantes Drehmoment</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="radio-betriebsmodus" class="radio-betriebsmodus"value="3">Konstante Leistung</label>
                                </div>
                            </fieldset>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- Streckeneinstellungen -->
                <div role="tabpanel" class="tab-pane tab-wrapper" id="tabStrecke">
                    <div class="input-group col-md-12 streckenauswahl-wrapper">
                        <div class="col-md-4 pull-left">
                            <label for="streckenauswahl">Streckenauswahl</label>
                            <fieldset name="streckenauswahl">
                                <div class="radio">
                                    <label><input type="radio" name="radio-strecke-id" class="radio-strecke-id" value="1" checked>leicht</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="radio-strecke-id" class="radio-strecke-id" value="2">mittel</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="radio-strecke-id" class="radio-strecke-id" value="3">schwer</label>
                                </div>

                            </fieldset>
                        </div>
                        <div class="col-md-8 pull-right">
                            <label for="streckenvorschau">Streckenvorschau</label>
                            <div id="streckenvorschau"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!-- Info -->
                <div role="tabpanel" class="tab-pane tab-wrapper" id="tabInfo">
                    <div class="col-md-8">
                        <ul id="infobox">
                            <li><b>Name:</b> CyNergy </li>
                            <li><b>Beschreibung:</b> Die Anwendung dient dazu, ein gemeinsames Trainingserlebnis mit
                                den zugehörigen Fahrradergometern und die direkte Nutzung der dabei erzeugten Energie
                                zu ermöglichen, zu verwalten und darzustellen.</li>
                            <br>
                            <li><b>Entwickler:</b> Enrico Costanzo, Alice Domandl, Vanessa Ferrarello, Clara Terbeck </li>
                            <li><b>Support:</b> Westfälische Hochschule Gelsenkirchen</li>
                            <li><b>Aktuelle Version:</b> v1.0.1 </li>
                            <li><b>Erscheinungsjahr:</b> 2017</li>
                            <br>
                            <li><b>Libraries & Frameworks:</b>
                                    <li><a href="https://laravel.com/" target="_blank">Laravel v5.3</a></li>
                                    <li><a href="http://getbootstrap.com" target="_blank">Bootstrap v3.3.7</a></li>
                                    <li><a href="https://jquery.com/" target="_blank">jQuery JavaScript Library v3.1.1</a></li>
                                    <li><a href="www.highcharts.com/license" target="_blank">Highcharts JS v5.0.6</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <img src="{{URL::asset('/img/cynergy_logo.png')}}" alt="CyNergy Logo" class="softwarelogo">
                    </div>
                </div>
            </div>
            <!-- Button Speichern -->
            <div class="col-md-6">
                <button type="button" class="btn btn-primary pull-right" id="btnSubmitEinstellungen">Speichern</button>
            </div>
            <!-- Button Hilfe -->
            <div class="col-md-6">
                <button type="button" class="btn btn-default pull-left" id="btnHilfeEinstellungen">Hilfe</button>
            </div>
        </form>
    </div>
</div>


