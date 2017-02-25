<!-- Hauptverantwortlich: Vanessa Ferrarello -->

<!-- Anzeige der Fahrerinformationen -->
<div class ="container-fluid">
    @foreach($fahrraeder as $fahrrad)
        <div class="panel panel-default pull-left fahrrad-information-panel ">
            <div class="panel-heading header-f{{ $fahrrad->id }}">
                <h3 class="panel-title panel-title-zd">Fahrrad #{{ $fahrrad->id }}</h3> <!-- Fahrernummer -->
            </div>
            <div class="panel-body panel-body-zd body-f{{ $fahrrad->id}}">
                <!--Anzeige, wenn das Fahrrad inaktiv ist -->
                <div id="fahrrad-inaktiv-wrapper-{{ $fahrrad->id}}" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 fahrerinformation">Fahrrad ist inaktiv</div>
                    </div>
                </div>
                <!-- Anzeige Timeout -->
                <div id="fahrrad-timeout-wrapper-{{ $fahrrad->id}}" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 fahrerinformation timeout-meldung">
                            <img src="{{URL::asset('/img/timeout_alert.png')}}" alt="Warnung" class="timeout-alert">
                            <span>Du bist seit längerer Zeit inaktiv.</span><br>
                            <span>Bitte fahre weiter, da du sonst in <div id="timeout-restzeit-{{ $fahrrad->id}}" style="display: inline"></div> sek. automatisch abgemeldet wirst.</span>
                        </div>
                </div>
                </div>
                <!-- Anzeige der Fahrerinformationen -->
                <div id="fahrrad-aktiv-wrapper-{{ $fahrrad->id}}" style="display: none;">
                    <!-- Fahrername -->
                    <div class="row">
                        <div class="col-lg-6 fahrerinformation">Fahrer:</div>
                        <div id="fahrername-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                    <div class="row">
                        <!-- Betriebsmodus, den der Fahrer fährt -->
                        <div class="col-lg-6 fahrerinformation">Modus:</div>
                        <div id="fahrermodus-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                    <div class="row">
                        <!-- aktuelle Geschwindigkeit des Fahrers -->
                        <div class="col-lg-6 fahrerinformation ">Geschwindigkeit:</div>
                        <div id="geschwindigkeit-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                    <div class="row">
                        <!-- Gesamtleistung des Fahrers -->
                        <div class="col-lg-6 fahrerinformation">Leistung:</div>
                        <div id="gesamtleistung-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                    <div class="row">
                        <!-- Zurückgelegte Kilometer des Fahrers -->
                        <div class="col-lg-6 fahrerinformation">Zurückgelegte Kilometer:</div>
                        <div id="strecke-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                    <div class="row">
                        <!-- Fahrdauer des Fahrers-->
                        <div class="col-lg-6 fahrerinformation">Fahrdauer:</div>
                        <div id="fahrdauer-anzeige-{{ $fahrrad->id }}" class="col-lg-6 fahrerinformation"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="clear"></div>
</div>