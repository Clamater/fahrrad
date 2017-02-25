<!-- Hauptverantwortlich: Vanessa Ferrarello -->

<!-- Anzeige des Highscores -->
<div class="panel panel-default pull-left highscore-panel">
    <div class="panel-heading panel-heading-zd">
        <h3 class="panel-title panel-title-zd">Highscore</h3>
    </div>
    <div class="panel-body panel-body-zd">
        <!-- 3 Platzierungen, die jeweils aus einem Icon, dem Fahrernamen und dem zugehörigen Wert bestehen -->
        <div class="row highscore-platzierung"> <!-- 1.Platz -->
            <div class="pull-left highscore-medaille-wrapper">
                <img src="{{URL::asset('/img/highscore_1.png')}}" alt="1.Platz" class="highscore-medaille">
            </div>
            <div class="pull-left highscore-name-wrapper">
                <span class="highscore-name" id="hs_name_1">-</span>
            </div>
            <div class="pull-left highscore-wert-wrapper">
                <span class="highscore-wert" id="hs_val_1">0 Wh</span>
            </div>
        </div>
        <div class="row highscore-platzierung"> <!-- 2.Platz -->
            <div class="pull-left highscore-medaille-wrapper">
                <img src="{{URL::asset('/img/highscore_2.png')}}" alt="2.Platz" class="highscore-medaille">
            </div>
            <div class="pull-left highscore-name-wrapper">
                <span class="highscore-name" id="hs_name_2">-</span>
            </div>
            <div class="pull-left highscore-wert-wrapper">
                <span class="highscore-wert" id="hs_val_2">0 Wh</span>
            </div>
        </div>
        <div class="row highscore-platzierung"> <!-- 3.Platz -->
            <div class="pull-left highscore-medaille-wrapper">
                <img src="{{URL::asset('/img/highscore_3.png')}}" alt="3.Platz" class="highscore-medaille">
            </div>
            <div class="pull-left highscore-name-wrapper">
                <span class="highscore-name" id="hs_name_3">-</span>
            </div>
            <div class="pull-left highscore-wert-wrapper">
                <span class="highscore-wert" id="hs_val_3">0 Wh</span>
            </div>
        </div>
    </div>
</div>