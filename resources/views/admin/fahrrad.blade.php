<!--
 Hauptverantwortlich: Clara Terbeck
-->
<div class="row">
    <div class="col-md-12 text-nowrap">
        <!-- Panel eines Fahrrades -->
        <div class="panel panel-default" id="panelAdmin" ondrop="drop(event)" ondragover="allowDrop(event)">
            <!-- Panelhead -->
            <div class="panel-heading" id="panelHeadingAdmin">
                <!-- Paneltitel -->
                <h3 class="panel-title pull-left" id="panelTitelAdmin">Fahrrad #{{$fahrrad->id}}</h3>
                <div class="pull-right">

                    <!-- Navigation eines aktiven Fahrrades -->
                    <div class="fahrradBtnAbmelden" style="display: {{ ($fahrrad->fahrer_id == null) ? "none" : "block"  }}">
                        <!-- Button "Zuordnung löschen" -->
                        <div class="pull-left">
                            <button type="button" class="btn btn-default btnAbmelden" id="{{$fahrrad->id}}">
                                <span class="glyphicon glyphicon-trash"></span>
                                Zuordnung löschen
                            </button>
                        </div>
                        <!-- Button "Hilfe" -->
                        <div class="pull-right">
                        <button type="button" class="btn btn-default btnHilfeAktiv">Hilfe</button>
                        </div>
                    </div>

                    <!-- Navigation eines inaktiven Fahrrades -->
                    <div class="fahrradBtnAnmelden" style="display: {{ ($fahrrad->fahrer_id == null) ? "block" : "none"  }}">
                        <!-- Button "Zuordnung hinzufügen" -->
                        <div class="pull-left">
                            <button type="button" class="btn btn-default btnAnmelden" id="{{$fahrrad->id}}">
                                <span class="glyphicon glyphicon-plus"></span>
                                Zuordnung hinzufügen
                            </button>
                        </div>
                        <!-- Button Hilfe -->
                        <div class="pull-right">
                            <button type="button" class="btn btn-default btnHilfeInaktiv ">Hilfe</button>
                        </div>
                    </div>
                </div>
                <span class="clearfix"></span>
            </div>

            <!-- Panelbody -->
            <div class="panel-body panelBodyAdmin" id="panelBodyAdmin-{{ $fahrrad->id }}">
                <!-- Fahrrad aktiv -->
                @if($fahrrad->fahrer_id)
                    <!-- Fahrer -->
                    <div class="row">
                        <div class="col-md-6">Fahrer:</div>
                        <div id="fahrername-anzeige-{{ $fahrrad->id }}" class="col-md-4">{{ $fahrrad->getFahrerName() }}</div>
                    </div>
                    <!-- Geschwindigkeit -->
                    <div class="row">
                        <div class="col-md-6 ">Geschwindigkeit</div>
                        <div id="geschwindigkeit-anzeige-{{ $fahrrad->id }}" class="col-md-4">{{ $fahrrad->geschwindigkeit }} km/h</div>
                    </div>
                    <!-- Aktuelle Leistung -->
                    <div class="row">
                        <div class="col-md-6">Aktuelle Leistung</div>
                        <div id="istLeistung-anzeige-{{ $fahrrad->id }}" class="col-md-4">{{ $fahrrad->istLeistung }} Watt</div>
                    </div>
                    <!-- Geschwindigkeit -->
                    <div class="row">
                        <div class="col-md-6">Zurückgelegte Strecke</div>
                        <div id="strecke-anzeige-{{ $fahrrad->id }}" class="col-md-4">{{ $fahrrad->strecke }} m</div>
                    </div>
                    <!-- Fahrdauer -->
                    <div class="row">
                        <div class="col-md-6">Fahrdauer</div>
                        <div id="fahrdauer-anzeige-{{ $fahrrad->id }}" class="col-md-4">00:00:00</div>
                    </div>
                    <!-- Betriebsmodus -->
                    <div class="row">
                        <form action="{{ url("fahrrad/".$fahrrad->id) }}" method="PUT">
                            <div class="col-md-6" id="betriebsmodusText">Betriebsmodus</div>
                            <div id="betriebsmodus-anzeige-{{ $fahrrad->id }}" class="col-md-4">
                                <select class="form-control" id="betriebsmodusAuswahlFahrrad">
                                    @foreach($modi as $modus)
                                        @if($fahrrad->modus_id == $modus->id)
                                            <option value="{{ $modus->id }}" selected>{{ $modus->name }}</option>
                                        @else
                                            <option value="{{ $modus->id }}">{{ $modus->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <!-- Nicht Streckenmodus -->
                    @if($fahrrad->modus_id != 1)
                        <div class="row modus">
                            <div class="col-md-6">Modus Option</div>
                            <div id="modus-option-{{ $fahrrad->id }}" class="col-md-3">
                                <!-- Drehmoment -->
                                @if($fahrrad->modus_id == 2)
                                    <input data-slider-id="modusSlider" id="{{ $fahrrad->modus_id }}" class="modus_option" type="text" data-provide="slider" data-slider-ticks="[3, 6, 9]" data-slider-ticks-labels='["leicht", "mittel", "schwer"]' data-slider-step="3" data-slider-value="{{ ($fahrrad->sollDrehmoment == null) ? 6 : $fahrrad->sollDrehmoment }}" data-slider-tooltip="hide"/>
                                <!--  Leistung  -->
                                @elseif($fahrrad->modus_id == 3)
                                    <input data-slider-id="modusSlider" id="{{ $fahrrad->modus_id }}" class="modus_option" type="text" data-provide="slider" data-slider-ticks="[30, 60, 90]" data-slider-ticks-labels='["leicht", "mittel", "schwer"]' data-slider-step="30" data-slider-value="{{ ($fahrrad->sollLeistung == null) ? 60 : $fahrrad->sollLeistung }}" data-slider-tooltip="hide"/>
                                @endif
                            </div>
                        </div>
                    @endif
                <!-- Fahrrad inaktiv -->
                @else
                    Fahrrad ist inaktiv
                @endif
            </div>
        </div>
    </div>
</div>