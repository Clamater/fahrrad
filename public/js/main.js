// WICHTIG: IP ändern, oder einfach localhost nehmen!
var BASE_PATH = "http://localhost/fahrrad/public/";

$(document).ready(function () {
    /*
     * Setup & Globale Variablen
     * */
    window.streckeData = { data: [], labels: [] };
    window.leistungData = { data: [], labels: [] };
    window.leistungAllAbsolute = 0;

    window.selectedUserRow = 0;
    window.selectedUserMode = 0;
    window.newUserTemp = {};

    $(".formAddFahrer").hide();

    /*
    * Aktualisierung der Daten: Charts und Fahredetails
    *
    * */
    window.setInterval(function () {
        // Updates der Charts nur auf den Seiten Central und Mobile
        if(window.location.href.includes("central") ||
           window.location.href.includes("mobile"))
        {
            updateDetails();
            updateCharts();
        }
    }, 1000);

    /*
     * autocomplete admin fahrer suche
     * */
    $("#q").autocomplete({
        source: "search/autocomplete",
        minLength: 1,
        select: function(event, ui) {
            $('#q').val(ui.item.label );
        },
        change: function(event, ui) {
        }
    });

    /*
    * Button bindings
    * */

    $(".panelBodyAdmin").on("change", "select", function (e, newValue) {
        $.ajax({
            url: $(this).closest("form").attr("action"),
            method: "PUT",
            data: { modus_id: $(this).val() }
        }).done(function (data, statusText, xhr){
            var status = xhr.status;

            if(status == 200){
                console.log("success");
            }
        });
    });

    $("#btnGenerateName").click(function(){
        var namen = ["test1", "test2", "test3", "test4", "test5", "test6"];

        // Gibt einen zufälligen Eintrag aus dem namen Array zurück
        var name = namen.sort(function() {return 0.5 - Math.random()})[0];

        $("#name").attr("value", name);
    });



    $(".btnAbmelden").each(function(){

       $( "#zuordnungLoeschen" ).dialog({
            autoOpen: false,
            dialogClass:"warnung",
            resizable: false,
        });

        $(this).click(function(e){

            var form = e.currentTarget.closest("form");

            $( "#zuordnungLoeschen" ).dialog( "open" );

            $( "#btnZuordnungLoeschenNein" ).on( "click", function() {
                $( "#zuordnungLoeschen" ).dialog( "close" );
            });

            //Erzeugt Bug
            $( "#btnZuordnungLoeschenJa" ).on( "click", function() {
                $( "#zuordnungLoeschen" ).dialog( "close" );

                $.ajax({
                    url: $(form).attr("action"),
                    method: $(form).attr("method")
                }).done(function (data, statusText, xhr){

                    var status = xhr.status;

                        if(status == 200){

                            var btnAbmelden = $(form).parents(".panel").find(".fahrradBtnAbmelden");
                            var btnAnmelden = $(form).parents(".panel").find(".fahrradBtnAnmelden");
                            var btnHilfeAktiv = $(form).parents(".panel").find(".fahrradBtnAbmelden");
                            var btnHilfeInaktiv = $(form).parents(".panel").find(".fahrradBtnAnmelden");

                            var fahrerdetailElement = $(form).parents(".panel").find(".panel-body");

                            $(fahrerdetailElement).html("Fahrrad ist inaktiv");

                            $(btnAbmelden).css("display", "none");
                            $(btnAnmelden).css("display", "block");
                            $(btnHilfeInaktiv).css("display", "block");
                            $(btnHilfeAktiv).css("display", "none");

                        }
                    });
            });
        });
    });

    $(".btnAnmelden").each(function(){

        $( "#keinFahrerAusgewaehlt" ).dialog({
            dialogClass:"fehler",
            resizable: false,
            autoOpen: false,
        });

        $(this).click(function(e){
            var form = e.currentTarget.closest("form");

            var btnAbmelden = $(form).parents(".panel").find(".fahrradBtnAbmelden");
            var btnAnmelden = $(form).parents(".panel").find(".fahrradBtnAnmelden");
            var btnHilfeAktiv = $(form).parents(".panel").find(".fahrradBtnAbmelden");
            var btnHilfeInaktiv = $(form).parents(".panel").find(".fahrradBtnAnmelden");

            var fahrerdetailElement = $(form).parents(".panel").find(".panel-body");

            if(window.selectedUserRow == 0){

                $( "#keinFahrerAusgewaehlt" ).dialog( "open" );

                $( "#btnCloseKeinFahrerAusgewaehlt" ).on( "click", function() {
                        $( "#keinFahrerAusgewaehlt" ).dialog( "close" );
                    });

            }else{

                $( "#keinFahrerAusgewaehlt" ).dialog({
                    dialogClass:"fehler",
                    resizable: false,
                    autoOpen: false,
                });

                // Zuordnen Request
                var url = $(form).attr("action") + "/fahrer/" + window.selectedUserRow;
                $.ajax({
                    url: url,
                    method: "GET",
                    data: {
                        modus_id: window.selectedUserMode
                    }
                }).done(function (data, statusText, xhr){
                    var status = xhr.status;

                    if(status == 200){
                        var fahrrad = data.fahrrad;
                        var fahrer = data.fahrer;

                        var template = '<div class="row">' +
                            '	<div class="col-md-6">Fahrer:</div>' +
                            '	<div id="fahrername-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrer.name + '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '	<div class="col-md-6 ">Geschwindigkeit</div>' +
                            '	<div id="geschwindigkeit-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.geschwindigkeit + ' km/h</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '	<div class="col-md-6">Gesamtleistung</div>' +
                            '	<div id="istLeistung-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.istLeistung + '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '	<div class="col-md-6">Zurückgelegte Strecke</div>' +
                            '	<div id="strecke-anzeige-' + fahrrad.id + '" class="col-md-4">' + fahrrad.strecke + '</div>' +
                            '</div>' +
                            '<div class="row">' +
                            '	<form action="./fahrrad/'+fahrrad.id+'" method="PUT">' +
                            '		<div class="col-md-6" id="betriebsmodusText">Betriebsmodus</div>' +
                            '		<div id="betriebsmodus-anzeige-' + fahrrad.id + '" class="col-md-4">' +
                            '			<select class="form-control" id="betriebsmodusAuswahlFahrrad">' +
                            '				<option value="1">Strecke</option>' +
                            '				<option value="2">Konstantes Drehmoment</option>' +
                            '				<option value="3">Konstante Leistung</option>' +
                            '			</select>' +
                            '		</div>' +
                            '	</form>' +
                            '</div>';

                        $(fahrerdetailElement).html(template);

                        $(btnAbmelden).css("display", "block");
                        $(btnAnmelden).css("display", "none");
                        $(btnHilfeInaktiv).css("display", "none");
                        $(btnHilfeAktiv).css("display", "block");

                        $(".radio-fahrer-id").each(function () {
                            $(this).prop('checked', false);
                        });

                        window.selectedUserRow = 0;
                        window.selectedUserMode = 0;
                    }
                });
            }
        });
    });

    $("#btnAddFahrer").click(function (e) {
        $(".formAddFahrer").show();
        $("#newUserTable").editableTableWidget();
    });

    $(".btnCancel").click(function (e) {
        $(".formAddFahrer").hide();
        //window.newUserTemp = {};
    });
    $(".btnSave").click(function (e) {
        var form = e.currentTarget.closest("form");

        $.ajax({
            url: $(form).attr("action"),
            method: $(form).attr("method"),
            data: window.newUserTemp
        }).done(function (data, statusText, xhr){
            var status = xhr.status;

            var template = '<tr draggable="true" id="' + data.id + '">' +
                '<th id="th_fahrer_id">' +
                    '<fieldset>' +
                        '<input type="radio" name="radio_fahrer_id" class="radio-fahrer-id" value="' + data.id + '">' +
                    '</fieldset>' +
                '</th>' +
                '<td id="name">' + data.name + '</td>' +
                '<td id="email">' + data.email + '</td>' +
                '<td id="gewicht">' + data.gewicht + '</td>' +
                '<td id="groesse">' + data.groesse + '</td>' +
                '<td id="betriebsmodus">' +
                    '<select class="form-control" id="betriebsmodusAuswahlFahrer">' +
                        '<option value="1">Strecke</option>' +
                        '<option value="2">Konstantes Drehmoment</option>' +
                        '<option value="3">Konstante Leistung</option>' +
                    '</select>' +
                '</td>' +
                '<th>' +
                    '<div class="btn btn-default btnDelete">' +
                        '<span class="glyphicon glyphicon-trash"></span>' +
                    '</div>' +
                '</th>' +
                '</tr>';

            $('#userTable tr:last').after(template);
            $("#userTable").editableTableWidget();

            window.newUserTemp = {};


            $(form).find("tbody > tr > td").each(function (index, value) {
                $(value).html("");
            });

            if(status == 200){
                //console.log("success");
            }
        });

        $(".formAddFahrer").hide();
    });


    $("#userTable").editableTableWidget();

    $( "#fahrerLoeschen" ).dialog({
        autoOpen: false,
        dialogClass:"warnung",
        resizable: false,
    });

    $("#userTable").on("click", ".btnDelete", function () {
        var form = $(this).closest("form");

        var fahrer_tr = $(this).parents("tr");
        var fahrer_id = $(fahrer_tr).attr("id");

        $( "#fahrerLoeschen" ).dialog( "open" );

        $.ajax({
            url: $(form).attr("action") + "/" + fahrer_id,
            method: "delete"
        }).done(function (data, statusText, xhr){
            var status = xhr.status;

            $( "#btnFahrerLoeschenNein" ).on( "click", function() {
                $( "#fahrerLoeschen" ).dialog( "close" );
            });

            $( "#btnFahrerLoeschenJa" ).on( "click", function() {
                $( "#fahrerLoeschen" ).dialog( "close" );

                if(status == 200){

                    $("#panelBodyAdmin-"+data.id).html("Fahrrad ist inaktiv");
                    //Anpassung der Hilfe- und Zuordnung-Buttons fehlt
                    $(fahrer_tr).remove();
                }

            });

        });
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
        }else if(changedElement == "betriebsmodus"){
            var selectVal = $(e.currentTarget).find("select").val();
            data = { modus_id : selectVal };
        }

        $.ajax({
            url: $(form).attr("action") + "/" + fahrer_id,
            method: $(form).attr("method"),
            data: data
        }).done(function (data, statusText, xhr){
            var status = xhr.status;

            if(status == 200){
                console.log("success");
            }
        });
    });
    $('#userTable').on('change', 'th', function(e, newValue) {
        var form = e.currentTarget.closest("form");

        var changedElement = $(this).attr("id");
        var fahrer_id = $(this).parents("tr").attr("id");

        var selectVal = $(form).find("select").val();

        $.ajax({
            url: $(form).attr("action") + "/" + fahrer_id,
            method: $(form).attr("method"),
            data: { modus_id : selectVal }
        }).done(function (data, statusText, xhr){
            var status = xhr.status;

            if(status == 200){
                console.log("success");
            }
        });
    });
    $('#newUserTable').on('change', 'td', function(e, newValue) {
        var form = e.currentTarget.closest("form");

        var changedElement = $(this).attr("id");
        var fahrer_id = $(this).parents("tr").attr("id");

        var data = null;
        if(changedElement == "name"){
            window.newUserTemp.name = newValue;
        }else if(changedElement == "email"){
            window.newUserTemp.email = newValue ;
        }else if(changedElement == "groesse"){
            window.newUserTemp.groesse = newValue;
        }else if(changedElement == "gewicht"){
            window.newUserTemp.gewicht = newValue ;
        }
    });

    $("#userTable").on("click", ".radio-fahrer-id", function () {
        window.selectedUserRow = $(this).val();
        window.selectedUserMode = $(this).parents("tr").find("select").val();
    });

    $("#hilfeTabelle").dialog({
        dialogClass:"hilfe",
        resizable: false,
        autoOpen: false,
    });
    $("#btnHilfeTabelle").on("click", function() {
        $("#hilfeTabelle").dialog("open");
    });

    $("#hilfeInaktiv").dialog({
        dialogClass:"hilfe",
        resizable: false,
        autoOpen: false,
    });
    $(".btnHilfeInaktiv").on("click", function() {
        $("#hilfeInaktiv").dialog("open");
    });

    $("#hilfeAktiv").dialog({
        dialogClass:"hilfe",
        resizable: false,
        autoOpen: false,
    });
    $(".btnHilfeAktiv").on("click", function() {
        $("#hilfeAktiv").dialog("open");
    });

    $("#addFahrer").dialog({
        dialogClass:"addFahrer",
        resizable: false,
        autoOpen: false,
        modal: true,

    });
    $("#btnAddFahrer").on("click", function(){
       $("#addFahrer").dialog("open");
        $('.ui-widget-overlay').addClass('custom-overlay');

    });


});

/*
 * Chart Functions
 * */

function updateChartData() {
    // Todo: Strecke id irgendwo her holen (hidden input zb)
    var strecke_id = 2;

    getDataFromAPI("strecke/" + strecke_id, true, function(response) {
        if(response && response.strecke ) {
            window.streckeData = { data: [], labels: [] };
            var gesamtlaenge = 0;
            $.each(response.strecke.abschnitte,
                function(index, value) {
                    gesamtlaenge += value.laenge;
                    window.streckeData.labels.push(gesamtlaenge); // X
                    window.streckeData.data.push(value.hoehe);  // Y
                }
            );
        }
    });

    getDataFromAPI("leistung", true, function(response) {
        if(response && response.fahrerleistung ) {
            window.leistungData = { data: [], labels: [] };
            window.leistungAllAbsolute = 0;

            $.each(response.fahrerleistung,
                function(index, value) {
                    window.leistungData.labels.push(value.name);
                    window.leistungData.data.push(value.istLeistung);

                    window.leistungAllAbsolute += value.istLeistung;
                }
            );
        }
    });
}

function updateCharts() {
    this.updateChartData();

    var track_chart = new Chart(document.getElementById("track"), {
        type: 'line',
        data: {
            labels: window.streckeData.labels, // X AXIS
            datasets: [
                {
                    type: "line",
                    label: "My First dataset",
                    fill: true,
                    data: window.streckeData.data // Y AXIS
                },
                {
                    type: "bubble",
                    label: "My First dataset",
                    fill: true,
                    data: [
                        {
                            x: 215,
                            y: 0,
                            r: 10
                        }
                    ] // Y AXIS
                }
            ]
        },
        options: {
            animation: false,
            scales: {
                xAxes: [{
                    display: true
                }],
                yAxes: [{
                    ticks: {
                        suggestedMax: 300,
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var energy_chart = new Chart(document.getElementById("energy-current"), {
        type: 'bar',
        data: {
            labels: window.leistungData.labels,
            datasets: [
                {
                    label: "My First dataset",
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    data: window.leistungData.data
                }
            ]
        },
        options: {
            animation: false,
            scales: {
                xAxes: [{
                    display: true
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
    var energy_all_chart = new Chart(document.getElementById("energy"), {
        type: 'bar',
        data: {
            labels: ["Leistung aller Fahrer"],
            datasets: [
                {
                    label: "My First dataset",
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    data: [window.leistungAllAbsolute]
                }
            ]
        },
        options: {
            animation: false,
            scales: {
                xAxes: [{
                    display: true
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
}

function updateFahrradKasten(fahrrad){
    var cond = fahrrad.fahrer_id == null;

    if(cond){
        $("#fahrrad-aktiv-wrapper-"+fahrrad.id).css("display", "none");
        $("#fahrrad-inaktiv-wrapper-"+fahrrad.id).css("display", "block");
    }else{
        $("#fahrrad-aktiv-wrapper-"+fahrrad.id).css("display", "block");
        $("#fahrrad-inaktiv-wrapper-"+fahrrad.id).css("display", "none");
    }

    var name             = cond ? "" : fahrrad.fahrer.name;
    var modus            = cond ? "" : fahrrad.modus.name;
    var geschwindigkeit  = cond ? "- km/h" : fahrrad.geschwindigkeit + " km/h";
    var istLeistung      = cond ? "- Watt" : fahrrad.istLeistung + " Watt";
    var strecke          = cond ? "- km" : (fahrrad.strecke / 1000) + " km";

    $("#fahrername-anzeige-"+fahrrad.id).html(name);
    $("#fahrermodus-anzeige-"+fahrrad.id).html(modus);
    $("#geschwindigkeit-anzeige-"+fahrrad.id).html(geschwindigkeit);
    $("#gesamtleistung-anzeige-"+fahrrad.id).html(istLeistung);
    $("#strecke-anzeige-"+fahrrad.id).html(strecke);
}

function updateDetails(){
    getDataFromAPI("data", true, function(response) {
        if(response && response.data ) {
            $.each( response.data.fahrrad,
                function(index, fahrrad) {
                    updateFahrradKasten(fahrrad);
                }
            );
        }
    });
    /*$.ajax({
        url: BASE_PATH + "data",
        type: 'get',
        dataType: 'json',
        async: true,
        success: function(response) {

        }
    });*/
}

function getDataFromAPI(url, async, successHandler){
    $.ajax({
        url: BASE_PATH + url,
        type: 'get',
        async: async,
        success: successHandler
    });
}