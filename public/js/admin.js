/*
 * Hauptverantwortlich: Enrico Costanzo
 * Teilverantwortlich:
 *      Vanessa Ferrarello
 *          Dialoge
 *      Clara Terbeck
 *          DataTable Initialisierung
 */

// WICHTIG: IP ändern, oder einfach localhost nehmen!
var BASE_PATH = "http://localhost/fahrrad/public/";

function getDataFromAPI(url, async, successHandler) {
    $.ajax({
        url: BASE_PATH + url,
        type: 'get',
        async: async,
        success: successHandler
    });
}

$(document).ready(function () {
    window.selectedUserRow = 0;
    window.selectedUserMode = 0;

    setInterval(function () {
        updateFahrradKasten();
    }, 2000);

    // Dialoginitialisierungen
    function initDialog(selector, dialogClass, modal, stack) {
        $(selector).dialog({
            autoOpen: false,
            dialogClass: dialogClass,
            resizable: false,
            modal: modal,
            stack: stack
        });
    }

    initDialog("#dialogFalschesPasswort",           "warnung",          true,   false);
    initDialog("#dialogZuordnungLoeschen",          "warnung",          true,   false);
    initDialog("#dialogEinstellungenSpeichern",     "warnung",          true,   false);
    initDialog("#dialogFahrerLoeschen",             "warnung",          true,   false);
    initDialog("#dialogValidationFailed",           "fehler",           true,   false);
    initDialog("#dialogKeinFahrerAusgewaehlt",      "fehler",           true,   false);
    initDialog("#dialogFahrerSchonZugeordnet",      "fehler",           true,   false);
    initDialog("#dialogFahrradIstBesetzt",          "fehler",           true,   false);
    initDialog("#dialogFahrernameSchonVergeben",    "fehler",           true,   false);
    initDialog("#dialogEinstellungen",              "einstellungen",    true,   false);
    initDialog("#addFahrer",                        "addFahrer",        true,   false);
    initDialog("#hilfeAktiv",                       "hilfe",            false,   false);
    initDialog("#hilfeInaktiv",                     "hilfe",            false,   false);
    initDialog("#hilfeTabelle",                     "hilfe",            false,   false);
    initDialog("#hilfeEinstellungen",               "hilfe",            false,   false);
    initDialog("#hilfeFahrer",                      "hilfe",            false,   true);


    // Close Button Fix
    $(".ui-dialog-titlebar-close").each(function () {
       $(this).css("color", "#000");
       $(this).css("font-size", "12px");
       $(this).html("X");
    });

    // Form Felder beim schließen leeren
    $('.addFahrer').on('dialogclose', function(event) {
        var form = $(this).find("#addFahrer").find("form");
        $(form).trigger("reset");
    });

    // Modus ändern (Fahrradkasten)
    $(".panelBodyAdmin").on("change", "select", function (e, newValue) {
        var context = $(this).parents(".panel-body");

        $.ajax({
            url: $(this).closest("form").attr("action"),
            method: "PUT",
            data: { modus_id: $(this).val() }
        }).done(function (data, statusText, xhr){
            if(xhr.status == 200){
                var el = $(context).find("div.row.modus");
                if(el.length > 0){
                    el.remove();
                }

                if(data.modus_id != 1){
                    var template =  '<div class="row modus">' +
                                    '	<div class="col-md-6">Modus Option</div>' +
                                    '<div id="modus-option-' + data.id + '" class="col-md-3">';
                        template += '<div id="modus-slider-' + data.id + '" class="modus_option"></div>';
                        template += '</div>' +
                                    '</div>';

                    $(context).append(template);
                }

                updateModusSlider(data.modus_id, data.id);
            }
        });
    });

    // Modus Option anpassen
    setInterval(function(){
        $(".modus_option").each(function(){
            $(this).off("change").on("change", function () {
                var value_html = $(this).parents(".row").find(".modus_value");

                var modus_id = $(this).parents(".row").find("#betriebsmodusAuswahlFahrrad").val();
                var modus_value = $(this).val();

                var einheit = (modus_id == 2) ? "Nm" : ((modus_id == 3) ? "W" : "");
                value_html.html($(this).val() + " " + einheit);

                var fahrrad_id = $(this).parents(".panelBodyAdmin").attr("id").split("-")[1];

                // Ajax /fahrrad/ID PUT - modus_id, modus_value
                $.ajax({
                    url: BASE_PATH + "fahrrad/" + fahrrad_id,
                    method: "PUT",
                    data: {
                        modus_id: modus_id,
                        modus_value: modus_value
                    }
                }).done(function (data, statusText, xhr){
                    if(xhr.status == 200){
                        console.log("modus value update: ", fahrrad_id, modus_id, modus_value);
                    }
                });
            });
        });
    },1000);

    function getRandomName(input_field) {
        //Zufallsnamen, max. 19 Zeichen
        var names = [
            "Athletische Ameise",
            "Schnelle Schnecke",
            "Flinker Fuchs",
            "Kräftiges Känguru",
            "Sportlicher Seehund",
            "Muskulöse Maus",
            "Beweglicher Biber",
            "Starke Schildkröte",
            "Rasantes Rentier",
            "Trainierter Tiger",
            "Aktiver Affe",
            "Dynamischer Dachs",
            "Springender Storch",
            "Wildes Wiesel",
            "Lebhafter Lemming",
            "Stürmischer Specht",
            "Eifriger Elch",
            "Forscher Frosch",
            "Fittes Faultier",
            "Strammer Springbock",
            "Wendiger Waschbär",
            "Flotte Fliege",
            "Eiliges Erdmännchen",
            "Leichtfüßiger Luchs",
            "Quirlige Qualle",
            "Energische Eule",
            "Schwungvoller Stier",
            "Zügiges Zebra",
            "Zackiger Zitteraal",
            "Agile Antilope"
        ];
        var result = [];

        getDataFromAPI("allnames", true, function (response) {
            names.map(function (name) {
                if(response.names.indexOf(name) == -1){
                    result.push(name);
                }
            });

            if(result.length > 0){
                // Gibt einen zufälligen Eintrag aus dem result Array zurück
                var istName = $(input_field).val();
                var sollName = null;
                do{
                    sollName = result.sort(function() {return 0.5 - Math.random()})[0];
                }while(istName == sollName);

                $(input_field).val(sollName);
            }else{
                console.log("Alle Zufallsnamen sind vergeben");
            }
        });
    }
    
    $("#btnGenerateName").click(function(){
        getRandomName("#fahrername");
    });

    $(".btnAnmelden").each(function(){
        $(this).click(function(e){
            var fahrrad_id = $(this).attr("id");
            var context = $(this).parents("#panelAdmin");

            if(window.selectedUserRow == 0){
                $("#dialogKeinFahrerAusgewaehlt").dialog({
                    buttons : {
                        "OK" : function() {
                            $(this).dialog("close");
                        }
                    }
                });
                $("#dialogKeinFahrerAusgewaehlt").dialog("open");
                $(".ui-widget-overlay").addClass('custom-overlay');
            }else{
                zuordnungHerstellen(context, fahrrad_id, window.selectedUserRow, window.selectedUserMode);

                $(".radio-fahrer-id").each(function () {
                    $(this).prop('checked', false);
                });

                window.selectedUserRow = 0;
                window.selectedUserMode = 0;
            }
        });
    });
    $(".btnAbmelden").each(function(){
        $(this).click(function(e){
            var fahrrad_id = $(this).attr("id");
            var context = $(this).parents("#panelAdmin");

            $("#dialogZuordnungLoeschen").dialog({
                buttons : {
                    "Ja" : function() {
                        zuordnungLoeschen(context, fahrrad_id);

                        $(this).dialog("close");
                    },
                    "Nein" : function() {
                        $(this).dialog("close");
                    }
                }
            });

            $("#dialogZuordnungLoeschen").dialog("open");
            $(".ui-widget-overlay").addClass('custom-overlay');
        });
    });

    $("#btnAddFahrer").on("click", function(){

        $("#addFahrer").dialog("open");
        $('.ui-widget-overlay').addClass('custom-overlay');
    });
    $("#btnSubmitAddFahrer").click(function (e) {
        var form = e.currentTarget.closest("form");

        var validation = validateInput(form);
        if(!validation.valid) {
            $("#dialogValidationFailed").dialog({
                buttons : {
                    "OK" : function() {
                        validation.err_fields.map(function (sel, i) {
                            $(sel).addClass("validation-error-border");

                            $(sel).closest(".validation-error-msg").css("display", "block");

                            var input_error_msg = ($(sel).parents(".validation-error-wrapper").find(".validation-error-msg"));
                            $(input_error_msg).html(validation.err[i]);
                        });
                        $(this).dialog("close");
                    }
                }
            });
            $("#dialogValidationFailed").dialog("open");
            $(".ui-widget-overlay").addClass('custom-overlay');
        }
        else{
            $.ajax({
                url: $(form).attr("action"),
                method: $(form).attr("method"),
                data: $(form).serialize()
            }).done(function (data, statusText, xhr)
            {
                var status = xhr.status;

                if(data.err == 0){
                    var element = $('#userTable tbody tr:last').attr("class");
                    var even_odd = element == "even" ? "odd" : "even";

                    var template = '<tr draggable="true" id="' + data.fahrer.id + '" ondragstart="drag(event)" role="row" class="' + even_odd + '">' +
                        '<th id="th_fahrer_id">' +
                        '<fieldset>' +
                        '<input type="radio" name="radio_fahrer_id" class="radio-fahrer-id" value="' + data.fahrer.id + '">' +
                        '</fieldset>' +
                        '</th>' +
                        '<td id="name">' + data.fahrer.name + '</td>' +
                        '<td id="email">' + data.fahrer.email + '</td>' +
                        '<td id="gewicht">' + data.fahrer.gewicht + '</td>' +
                        '<td id="groesse">' + data.fahrer.groesse + '</td>' +
                        '<th id="betriebsmodus">' +
                        '<select class="form-control" id="betriebsmodusAuswahlFahrer">' +
                        '<option value="1" ' + ((data.fahrer.modus_id == 1) ? 'selected' : '') + '>Strecke</option>' +
                        '<option value="2" ' + ((data.fahrer.modus_id == 2) ? 'selected' : '') + '>Konstantes Drehmoment</option>' +
                        '<option value="3" ' + ((data.fahrer.modus_id == 3) ? 'selected' : '') + '>Konstante Leistung</option>' +
                        '</select>' +
                        '</th>' +
                        '<th>' +
                        '<div class="btn btn-default btnDelete">' +
                        '<span class="glyphicon glyphicon-trash"></span>' +
                        '</div>' +
                        '</th>' +
                        '</tr>';

                    var num_rows = $("#userTable tr").length - 1;
                    if(num_rows == 1 && $("#userTable tbody tr:first td").length == 1){
                        $("#userTable tbody").html("");
                    }

                    $('#userTable tbody').append(template);

                    setTimeout(function () {
                        $("#userTable").editableTableWidget();
                    },500);

                    $("#addFahrer").dialog("close");
                }else if(data.err == 1){
                    $("#dialogFahrernameSchonVergeben").dialog({
                        buttons : {
                            "OK" : function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    $("#dialogFahrernameSchonVergeben").dialog("open");
                    $(".ui-widget-overlay").addClass('custom-overlay');
                }
            });
        }
    });

    // Validierung
    function numbersOnly(e) {
        e.value = e.value.replace(/[^0-9\.,]/g,'');
    }
    $("input#groesse.form-control").keyup(function () {
        numbersOnly(this);
    });
    $("input#gewicht.form-control").keyup(function () {
        numbersOnly(this);
    });


    /*
    * Fixme:
    * Neue Zeilen werden im DOM hinzugefügt, das DataTable Plugin kann aber DOM Änderungen nicht neu laden.
    * Daher sollten diese per rows.add() Methode eingefügt / per rows.delete() Methode gelöscht werden.
    * Dies erfordert eine neue Implementierung der Tabelle.
    * */
    $("#userTable").DataTable({
        "language": {
            search: "_INPUT_",
            url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json"
        }
    });
    $("#userTable").editableTableWidget();
    $("#userTable").on("click", ".btnDelete", function () {
        var form = $(this).closest("form");

        var fahrer_tr = $(this).parents("tr");
        var fahrer_id = $(fahrer_tr).attr("id");

        $("#dialogFahrerLoeschen").dialog({
            buttons : {
                "Ja" : function() {
                    $.ajax({
                        url: $(form).attr("action") + "/" + fahrer_id,
                        method: "delete"
                    }).done(function (data, statusText, xhr){
                        var status = xhr.status;

                        if(status == 200){
                            $(fahrer_tr).remove();

                            var num_rows = $("#userTable tr").length - 1;
                            if(num_rows == 0){
                                var template_empty = '<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">Keine Daten in der Tabelle vorhanden</td></tr>';
                                $("#userTable tbody").html(template_empty);
                            }

                            var fahrrad_kasten = $("#panelBodyAdmin-"+data.id);
                            fahrrad_kasten.html("Fahrrad ist inaktiv");

                            var buttons = fahrrad_kasten.siblings(".panel-heading").find(".pull-right");
                            var btnAbmelden = buttons.find(".fahrradBtnAbmelden");
                            var btnAnmelden = buttons.find(".fahrradBtnAnmelden");

                            $(btnAbmelden).css("display", "none");
                            $(btnAnmelden).css("display", "block");
                        }
                    });

                    $(this).dialog("close");
                },
                "Nein" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialogFahrerLoeschen").dialog("open");
        $('.ui-widget-overlay').addClass('custom-overlay');
    });
    $('#userTable').on('change', 'td', function(e, newValue) {
        var form = e.currentTarget.closest("form");

        var changedElement = $(this).attr("id");
        var fahrer_id = $(this).parents("tr").attr("id");

        var data = null;
        if(changedElement == "name"){
            data = { name : newValue };
        }else if(changedElement == "email"){
            data = { email : newValue || "_"};
        }else if(changedElement == "groesse"){
            data = { groesse : newValue };
        }else if(changedElement == "gewicht"){
            data = { gewicht : newValue };
        }

        $.ajax({
            url: $(form).attr("action") + "/" + fahrer_id,
            method: $(form).attr("method"),
            data: data
        });
    });
    $('#userTable').on('change', 'th', function(e, newValue) {
        var form = e.currentTarget.closest("form");

        var changedElement = $(this).attr("id");
        var fahrer_id = $(this).parents("tr").attr("id");

        var selectVal = $(e.currentTarget).find("select").val();

        $.ajax({
            url: $(form).attr("action") + "/" + fahrer_id,
            method: $(form).attr("method"),
            data: { modus_id : selectVal }
        });
    });
    $("#userTable").on("click", ".radio-fahrer-id", function () {
        window.selectedUserRow = $(this).val();
        window.selectedUserMode = $(this).parents("tr").find("select").val();
    });

    $("#btnHilfeTabelle").on("click", function() {
        $("#hilfeTabelle").dialog("open");
    });
    $(".btnHilfeInaktiv").on("click", function() {
        $("#hilfeInaktiv").dialog("open");
    });
    $(".btnHilfeAktiv").on("click", function() {
        $("#hilfeAktiv").dialog("open");
    });
    $("#btnHilfeFahrer").on("click", function() {
        $("#hilfeFahrer").dialog("open");
    });

    $("#btnEinstellungen").on("click", function(){
        $("#dialogEinstellungen").dialog("open");
        $('.ui-widget-overlay').addClass('custom-overlay');
    });
    $("#btnSubmitEinstellungen").on("click", function() {
        $("#dialogEinstellungenSpeichern").dialog({
            buttons : {
                "Ja" : function() {
                    //Speichern
                    var id = $(".radio-strecke-id:checked").val();
                    createCookie("strecke", id, 1);

                    $(this).dialog("close");
                    $("#dialogEinstellungen").dialog("close");
                },
                "Nein" : function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#dialogEinstellungenSpeichern").dialog("open");
        $(".ui-widget-overlay").addClass('custom-overlay');
    });

    $("#btnHilfeEinstellungen").on("click", function() {
            $("#hilfeEinstellungen").dialog("open");
        });

    // Einstellungen Streckeauswahl

    window.strecke_vorschau_data = {data: [], labels: []};
    window.strecke_vorschau_abschnitte = [];
    window.chart_strecke_vorschau = new Highcharts.Chart({
        title: {
            text: ''
        },
        chart: {
            renderTo: 'streckenvorschau',
            animation: true,
            backgroundColor: "#E6E9ED",
            spacingBottom: 10,
            spacingTop: 10,
            spacingLeft: 10,
            spacingRight: 10
        },
        legend: {
            enabled: false
        },
        xAxis: {
           // visible: false,
           // min:0.5,
            labels: {
                formatter: function () {
                    if (window.strecke_vorschau_data.labels[this.value]) return window.strecke_vorschau_data.labels[this.value] + " m";
                },
                style: {
                    fontSize: '14px'
                },
                rotation: -45,
            }
        },
        yAxis: {
            min: 0,
            max: 20,
            labels: {
                format: '{value}',
                style: {
                    fontSize: '18px'
                }
            },
            title: {
                text: 'Höhe (Meter)',
                x: -10,
                style: {
                    fontSize: '16px'
                }
            }
        },
        plotOptions: {
            area: {
                fillOpacity: 0.5,
                marker: {
                    enabled: true
                }
            }
        },
        tooltip: {
            enabled: false
        },
        series: [{
            data: window.strecke_vorschau_data.data,
            type: 'area',
            color: '#AAB2BD',
            enableMouseTracking: false,
            zIndex: 5,
        }]
    });

    updateChartStreckeData(1);

    $(".radio-strecke-id").change(function (e) {
        updateChartStreckeData($(e.target).val());
    })
});

function zuordnungLoeschen(context, fahrrad_id){
    $.ajax({
        url: BASE_PATH + "fahrrad/" + fahrrad_id,
        method: "DELETE",
        async: false
    }).done(function (data, statusText, xhr){
        if(xhr.status == 200){
            initFahrradKasten(context, false);
        }
    });
}
function zuordnungHerstellen(context, fahrrad_id, fahrer_id, modus_id){
    var url =  BASE_PATH + "fahrrad/" + fahrrad_id + "/fahrer/" + fahrer_id;

    $.ajax({
        url: url,
        method: "GET",
        data: {
            modus_id: modus_id
        }
    }).done(function (data, statusText, xhr) {
        var status = xhr.status;

        if (status == 200) {
            if(typeof data.err == "undefined"){
                var fahrrad = data.fahrrad;
                var fahrer = data.fahrer;

                initFahrradKasten(context, true, fahrrad, fahrer);
            }else{
                if(data.err == 1){
                    // Fahrer schon zugeordnet
                    $("#dialogFahrerSchonZugeordnet").dialog({
                        buttons : {
                            "OK" : function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    $("#dialogFahrerSchonZugeordnet").dialog("open");
                    $(".ui-widget-overlay").addClass('custom-overlay');
                }else if(data.err == 2){
                    // Fahrrad ist besetzt

                    $("#dialogFahrradIstBesetzt").dialog({
                        buttons : {
                            "OK" : function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    $("#dialogFahrradIstBesetzt").dialog("open");
                    $(".ui-widget-overlay").addClass('custom-overlay');
                }
            }
        }
    });
}

// Wird nach dem herstellen/löschen einer Zuordnung aufgerufen um den Kasten zu aktualisieren
function initFahrradKasten(context, modus, fahrrad, fahrer){
    // Optionale Parameter nur bei Zuordnung herstellen nötig
    fahrrad = fahrrad || null;
    fahrer  = fahrer  || null;

    var btnAbmelden = $(context).find(".fahrradBtnAbmelden");
    var btnAnmelden = $(context).find(".fahrradBtnAnmelden");

    var fahrerdetailElement = $(context).find(".panel-body");

    if(modus){ // Zuordnung Herstellen
        if(fahrrad && fahrer){
            var template = '<div class="row">' +
                '	<div class="col-md-6">Fahrer:</div>' +
                '	<div id="fahrername-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrer.name + '</div>' +
                '</div>' +
                '<div class="row">' +
                '	<div class="col-md-6 ">Geschwindigkeit:</div>' +
                '	<div id="geschwindigkeit-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.geschwindigkeit + ' km/h</div>' +
                '</div>' +
                '<div class="row">' +
                '	<div class="col-md-6">Leistung:</div>' +
                '	<div id="istLeistung-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.istLeistung + '</div>' +
                '</div>' +
                '<div class="row">' +
                '	<div class="col-md-6">Zurückgelegte Strecke:</div>' +
                '	<div id="strecke-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.strecke + '</div>' +
                '</div>' +
                '<div class="row">' +
                '   <div class="col-md-6">Fahrdauer:</div>' +
                '   <div id="fahrdauer-anzeige-' + fahrrad.id + '" class="col-md-4">00:00:00</div>' +
                '</div>' +
                '<div class="row">' +
                '	<form action="./fahrrad/'+fahrrad.id+'" method="PUT">' +
                '		<div class="col-md-6" id="betriebsmodusText">Betriebsmodus</div>' +
                '		<div id="betriebsmodus-anzeige-' + fahrrad.id + '" class="col-md-4">' +
                '			<select class="form-control" id="betriebsmodusAuswahlFahrrad">' +
                '				<option value="1"' + ((fahrer.modus_id == 1) ? ' selected' : '') + '>Strecke</option>' +
                '				<option value="2"' + ((fahrer.modus_id == 2) ? ' selected' : '') + '>Konstantes Drehmoment</option>' +
                '				<option value="3"' + ((fahrer.modus_id == 3) ? ' selected' : '') + '>Konstante Leistung</option>' +
                '			</select>' +
                '		</div>' +
                '	</form>' +
                '</div>';

            if(fahrrad.modus_id != 1){ // Nicht Streckenmodus
                template += '<div class="row modus">' +
                    '	<div class="col-md-6">Modus Option</div>' +
                    '<div id="modus-option-' + fahrrad.id + '" class="col-md-3">';
                template += '<div id="modus-slider-' + fahrrad.id + '" class="modus_option"></div>';
                template += '</div>' +
                            '</div>';
            }

            $(fahrerdetailElement).html(template);

            updateModusSlider(fahrrad.modus_id, fahrrad.id);

            $(btnAbmelden).css("display", "block");
            $(btnAnmelden).css("display", "none");
        }
    }
    else{ // Zuordnung Löschen
        $(fahrerdetailElement).html("Fahrrad ist inaktiv");

        $(btnAbmelden).css("display", "none");
        $(btnAnmelden).css("display", "block");
    }
}

function updateModusSlider(modus, id){
    if(modus == 2){ // Drehmoment
        $("#modus-slider-" + id).slider({
            id: "modusSlider",
            min: 3,
            max: 9,
            step: 3,
            ticks: [3,6,9],
            ticks_labels: ["leicht", "mittel", "schwer"],
            value: 6,
            tooltip: "hide"
        });
    }else if(modus == 3){ // Leistung
        $("#modus-slider-" + id).slider({
            id: "modusSlider",
            min: 30,
            max: 90,
            step: 30,
            ticks: [30,60,90],
            ticks_labels: ["leicht", "mittel", "schwer"],
            value: 60,
            tooltip: "hide"
        });
    }
}

// Im Intervall 2s ausführen
function updateFahrradKasten() {
    getDataFromAPI("data", true, function (response) {
        if (response && response.data) {
            $.each(response.data.fahrrad, function (index, fahrrad) {
                var fahrerdetailElement = $("#panelBodyAdmin-" + fahrrad.id);

                var btnAbmelden = fahrerdetailElement.parents("#panelAdmin").find(".fahrradBtnAbmelden");
                var btnAnmelden = fahrerdetailElement.parents("#panelAdmin").find(".fahrradBtnAnmelden");

                if(fahrrad.fahrer != null){
                    var fahrdauer = getElapsedTime(fahrrad.zugeordnet_at);

                    $("#fahrername-anzeige-" + fahrrad.id).html(fahrrad.fahrer.name);
                    $("#geschwindigkeit-anzeige-" + fahrrad.id).html(fahrrad.geschwindigkeit + " km/h");
                    $("#istLeistung-anzeige-" + fahrrad.id).html(fahrrad.istLeistung + " Watt");
                    $("#strecke-anzeige-" + fahrrad.id).html(fahrrad.strecke + " m");
                    $("#fahrdauer-anzeige-" + fahrrad.id).html(fahrdauer);

                    $(btnAbmelden).css("display", "block");
                    $(btnAnmelden).css("display", "none");
                }else{
                    fahrerdetailElement.html("Fahrrad ist inaktiv");

                    $(btnAbmelden).css("display", "none");
                    $(btnAnmelden).css("display", "block");
                }
            });
        }
    });
}

// Validiert die Eingaben beim Erstellen eines Fahrers
function validateInput(form){
    var validates = true;
    var validation_errors = [];
    var validation_errors_fields = [];

    var selName = "#fahrername";
    var name = $(form).find(selName).val();
    if(!name){
        validates = false;
        validation_errors.push("Name nicht vorhanden");
        validation_errors_fields.push(selName);
    }

    if(name.length > 19){
        validates = false;
        validation_errors.push("Name darf nicht länger als 19 Zeichen sein");
    }

    var selEmail = "input#email";
    var email = $(form).find(selEmail).val();
    var regex_email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(email){
        if(regex_email.test(email) == false){
            validates = false;
            validation_errors.push("E-Mail hat falsches Format");
            validation_errors_fields.push(selEmail);
        }
    }

    var modus = $('input[name=betriebsmodus]:checked', form).val();
    if(!modus){
        validates = false;
        validation_errors.push("Kein Modus ausgewählt");
    }

    var selGroesse = "input#groesse";
    var groesse = $(form).find(selGroesse).val();
    if(groesse){
        try{
            validates = $.isNumeric(groesse);
        }catch (ex){
            validates = false;
            validation_errors.push("Größe ist kein valider Wert");
            validation_errors_fields.push(selGroesse);
        }
    }

    var selGewicht = "input#gewicht";
    var gewicht = $(form).find(selGewicht).val();
    if(gewicht){
        try{
            validates = $.isNumeric(gewicht);
        }catch (ex){
            validates = false;
            validation_errors.push("Gewicht ist kein valider Wert");
            validation_errors_fields.push(selGewicht);
        }
    }

    return { valid: validates, err: validation_errors, err_fields: validation_errors_fields };
}

// Drag & Drop
function allowDrop(ev) {
    ev.preventDefault();
}
function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    ev.dataTransfer.setData("modus", $(ev.target).find("#betriebsmodus").find("select").val());
}
function drop(ev) {
    ev.preventDefault();

    var target = $(ev.target);

    if(!target.hasClass("panel-body")) {
        target = $(target.parents(".panel-body"));
    }

    var fahrrad_id = target.attr("id").split("-")[1];
    var user_id = ev.dataTransfer.getData("text");
    var modus_id = ev.dataTransfer.getData("modus");
    var context = target.parents("#panelAdmin");

    zuordnungHerstellen(context, fahrrad_id, user_id, modus_id);
}

function getElapsedTime(fahrrad_timestamp) {
    var current_timestamp = Math.floor(new Date().getTime() / 1000);
    var elapsed_seconds = (current_timestamp - fahrrad_timestamp);

    var d = new Date(null);
    d.setSeconds(elapsed_seconds); // Sekunden zu JS Date Objekt
    d.setTime(d.getTime() + d.getTimezoneOffset() * 60 * 1000); // Zeitzone anpassen!

    // String zusammenbauen
    return ('0' + d.getHours()).slice(-2) + ':' + ('0' + (d.getMinutes())).slice(-2) + ':' + ('0' + (d.getSeconds() + 1)).slice(-2);
}

function updateChartStreckeData(id) {
    getDataFromAPI("strecke/" + id, false, function (response) {
        if (response && response.strecke) {
            window.strecke_vorschau_abschnitte = [];
            window.strecke_vorschau_data = {data: [0], labels: [0]};
            var gesamtlaenge = 0;

            $.each(response.strecke.abschnitte,
                function (index, value) {
                    gesamtlaenge += value.laenge;
                    window.strecke_vorschau_data.labels.push(gesamtlaenge); // X
                    window.strecke_vorschau_data.data.push(value.hoehe);  // Y

                    window.strecke_vorschau_abschnitte.push(value.id);
                }
            );

            window.chart_strecke_vorschau.series[0].setData(window.strecke_vorschau_data.data);
            window.chart_strecke_vorschau.xAxis[0].setCategories(window.strecke_vorschau_data.labels);
            window.chart_strecke_vorschau.redraw();
        }
    });
}

function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}