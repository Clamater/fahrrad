// WICHTIG: IP ändern, oder einfach localhost nehmen!
var BASE_PATH = "http://localhost/fahrrad/public/";

$(document).ready(function () {
    //Setup & Globale Variablen
    window.strecke_id = 2; // Todo: Strecke id irgendwo her holen (hidden input zb)

    // Datenobjekte
    window.fahrrad = [];
    window.streckeData = { data: [], labels: [] };
    window.strecke_abschnitte = [];
    window.strecke_fahrrad_abschnitte = [0,0,0];
    window.fahrrad_strecke = { data: [] };
    window.leistungData = { data: [], labels: [] };
    window.gesamtleistungData = { absolute: 0, data: [] };

    // Einmalig Strecke laden
    updateChartStreckeData();

    // Chart Definitionen
    window.chart_strecke = new Highcharts.Chart({
        title:{
            text:'',
        },
        chart: {
            renderTo: 'container-strecke',
            animation: true,
            backgroundColor: "#E6E9ED",
            spacingBottom: 10,
            spacingTop: 10,
            spacingLeft: 10,
            spacingRight: 10,
            events: {
                load: function(){
                    renderFahrerPositionen(this);
                },
                redraw: function(){
                    renderFahrerPositionen(this);
                }
            }
        },
        legend: {
            enabled: false
        },
        xAxis: {
            labels: {
                formatter: function() {
                    if(window.streckeData.labels[this.value]) return window.streckeData.labels[this.value].toFixed(2) + " m";
                },
                style: {
                    fontSize: '14px',
                }
            }
        },
        yAxis: {
            min: 0,
            max: 250,
            labels: {
                format: '{value}',
                style:{
                    fontSize:'18px',
                }
            },
            title: {
                text: 'Höhe (Meter)',
                x: -10,
                style:{
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
            data: window.streckeData.data,
            type: 'area',
            color: '#AAB2BD',
            enableMouseTracking: false,
            zIndex: 5
        }]
    });
    window.chart_leistung = new Highcharts.Chart({
        title:{
            text:''
        },
        chart: {
            renderTo: 'container-leistung',
            type: 'column',
            backgroundColor: "#E6E9ED",
            spacingBottom: 10,
            spacingTop: 10,
            spacingLeft: 10,
            spacingRight: 10
        },
        xAxis: {
            categories: window.leistungData.labels,
            crosshair: true,
            labels:{
                style:{
                    fontSize:'16px',
                }
            }
        },
        yAxis: {
            gridLineWidth: 1,
            gridLineColor: '#D0D3D6',
            min: 0,
            title: {
                text: 'Leistung (Watt)',
                x: -10,
                style: {
                    fontSize: '16px',
                }
            },
            labels:{
                style:{
                    fontSize:'18px',
                }
            }
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                colorByPoint: true
            }
        },
        colors: [
            '#EC87C0',
            '#5D9CEC',
            '#FFCE54'
        ],
        series: [{
            showInLegend: false,
            data: window.leistungData.data
        }]
    });
    window.chart_gesamtleistung = new Highcharts.Chart({
        chart: {
            renderTo: 'container-gesamtleistung',
            type: 'pie',
            backgroundColor: "#E6E9ED",
            margin: [30, 0, 0, 0],
            spacingTop: 0,
            spacingBottom: 0,
            spacingLeft: 0,
            spacingRight: 0
        },
        title: {
            text: '0 W',
            style: {
                fontSize: '30px'
            }
        },
        tooltip: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: false,
                dataLabels: {
                    enabled: false
                }
            }
        },
        colors: [
            '#EC87C0',
            '#5D9CEC',
            '#FFCE54'
        ],
        series: [{
            colorByPoint: true,
            data: []
        }]
    });

    //Aktualisierung der Daten: Charts, Fahredetails und Fahrerpositionen
    window.setInterval(function () {
        updateDetails();
        updateChartStreckeFahrer();
        updateStatistik();
        updateBatterie();
    }, 1000);
});

function updateStatistik() {
    getDataFromAPI("statistik", false, function(response) {
        if(response && response.statistik ) {
            var statistik = response.statistik;
            $("#statistik_teilnehmer").html(statistik.teilnehmer);
            $("#statistik_gesamtstrecke").html(statistik.kilometer);
            $("#statistik_hoehenmeter").html(statistik.hoehenmeter);
            $("#statistik_energie").html(statistik.energie);
        }
    });
}

function updateBatterie() {
    getDataFromAPI("batterie", false, function(response) {
        if(response && response.batterie ) {
            var batterie = response.batterie;

            var modus = "";
            if(batterie.laststrom > batterie.generatorstrom){
                modus = "discharge";
            }else{
                modus = "charge";
            }

            var modus_num = 0;
            if(batterie.spannung <= 11.8){ // Batterie leer
                modus_num = 0;
            }else if(batterie.spannung > 11.8 && batterie.spannung <= 12.2){
                modus_num = 1;
            }else if(batterie.spannung > 12.2 && batterie.spannung <= 12.6){
                modus_num = 2;
            }else if(batterie.spannung > 12.6 && batterie.spannung <= 13.0){
                modus_num = 3;
            }else if(batterie.spannung > 13.0 && batterie.spannung <= 13.4){
                modus_num = 4;
            }else if(batterie.spannung > 13.4 && batterie.spannung <= 13.8){
                modus_num = 5;
            }else if(batterie.spannung >= 13.8){
                modus_num = 5;
            }

            var class_name = "batterie " + modus + modus_num;
            $("#batterieladung").attr("class", class_name);
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
    var fahrdauer        = cond ? "00:00:00" : getElapsedTime(fahrrad.zugeordnet_at);

    $("#fahrername-anzeige-"+fahrrad.id).html(name);
    $("#fahrermodus-anzeige-"+fahrrad.id).html(modus);
    $("#geschwindigkeit-anzeige-"+fahrrad.id).html(geschwindigkeit);
    $("#gesamtleistung-anzeige-"+fahrrad.id).html(istLeistung);
    $("#strecke-anzeige-"+fahrrad.id).html(strecke);
    $("#fahrdauer-anzeige-"+fahrrad.id).html(fahrdauer);
}

function getElapsedTime(fahrrad_timestamp) {
    var current_timestamp = Math.floor(new Date().getTime() / 1000);
    var elapsed_seconds = (current_timestamp - fahrrad_timestamp);

    var d = new Date(null);
    d.setSeconds(elapsed_seconds); // Sekunden zu JS Date Objekt
    d.setTime( d.getTime() + d.getTimezoneOffset()*60*1000 ); // Zeitzone anpassen!

    // String zusammenbauen
    return ('0' + d.getHours()).slice(-2) + ':' + ('0' + (d.getMinutes())).slice(-2) + ':' + ('0' + (d.getSeconds()+1)).slice(-2);
}

function updateDetails(){
    getDataFromAPI("data", false, function(response) {
        if(response && response.data ) {
            window.leistungData = { data: [], labels: []};
            window.fahrrad_strecke = { data: []};
            window.gesamtleistungData = { absolute: 0, data: [] };

            for(var j = 0; j < window.fahrrad.length; j++){
                if(window.fahrrad[j].element != null){
                    window.fahrrad[j].element.element.remove();
                }
            }
            window.fahrrad = [];

            $.each( response.data.fahrrad,
                function(index, fahrrad) {
                    updateFahrradKasten(fahrrad);

                    if(fahrrad.fahrer_id != null){
                        addFahrrad(fahrrad.id, fahrrad.color, fahrrad.modus_id, fahrrad.abschnitt_id);

                        window.leistungData.labels.push(fahrrad.fahrer.name);
                        window.leistungData.data.push(fahrrad.istLeistung);

                        window.gesamtleistungData.absolute += fahrrad.istLeistung;

                        window.fahrrad_strecke.data.push(fahrrad.strecke);
                    }
                }
            );

            for(var i = 0; i < window.fahrrad.length; i++){
                window.fahrrad[i].gefahrene_strecke = window.fahrrad_strecke.data[i];
            }
        }
    });

    window.chart_leistung.series[0].setData(window.leistungData.data);
    window.chart_leistung.xAxis[0].setCategories(window.leistungData.labels);
    window.chart_leistung.redraw();

    // Gesamtleistung
    for(var i = 0; i <= window.leistungData.data.length - 1; i++){
        window.gesamtleistungData.data.push([
            window.leistungData.labels[i],
            Math.floor((window.leistungData.data[i] * 100) / window.gesamtleistungData.absolute)
        ]);
    }

    window.chart_gesamtleistung.setTitle({
        text: window.gesamtleistungData.absolute + " W"
    });

    window.chart_gesamtleistung.series[0].setData(window.gesamtleistungData.data);
    window.chart_gesamtleistung.redraw();
}

// Lädt die Daten zur Strecke mit der angegebenen ID von der API
function updateChartStreckeData() {
    getDataFromAPI("strecke/" + window.strecke_id, false, function (response) {
        if (response && response.strecke) {
            window.strecke_abschnitte = [];
            window.streckeData = {data: [0], labels: [0]};
            var gesamtlaenge = 0;

            $.each(response.strecke.abschnitte,
                function (index, value) {
                    gesamtlaenge += value.laenge;
                    window.streckeData.labels.push(gesamtlaenge); // X
                    window.streckeData.data.push(value.hoehe);  // Y

                    window.strecke_abschnitte.push(value.id);
                }
            );
        }
    });
}

function renderFahrerPositionen(chart){
    for(var i = 0; i < window.fahrrad.length; i++){
        var fahrrad = window.fahrrad[i];

        var pixelX = chart.xAxis[0].toPixels(fahrrad.x || 0);
        var pixelY = chart.yAxis[0].toPixels(fahrrad.y || 0);

        if(fahrrad.element != null){
            fahrrad.element.element.remove();
            fahrrad.element = null;
        }

        if(fahrrad.modus == 1){
            fahrrad.element = chart.renderer.circle(pixelX, pixelY, 10).attr({
                fill: fahrrad.color,
                zIndex: 10
            }).add();
        }
    }
}

function updateChartStreckeFahrer(){
    var strecke = window.chart_strecke.series[0];

    for(var i = 0; i < window.fahrrad.length; i++){
        for(var abschnitt = 0; abschnitt < strecke.points.length; abschnitt++){
            if(window.fahrrad[i].gefahrene_strecke < window.streckeData.labels[abschnitt+1]){
                var abschnitt_len 	  = window.streckeData.labels[abschnitt+1] - window.streckeData.labels[abschnitt];
                var rest_strecke 	  = window.fahrrad[i].gefahrene_strecke - window.streckeData.labels[abschnitt];
                var rest_prozent 	  = (rest_strecke)/abschnitt_len;

                var pStart = {
                    x: window.streckeData.labels[abschnitt],
                    y: window.streckeData.data[abschnitt]
                };

                var pEnde = {
                    x: window.streckeData.labels[abschnitt+1],
                    y: window.streckeData.data[abschnitt+1]
                };

                var x_wert = (parseFloat(abschnitt) + parseFloat(rest_prozent)).toFixed(2);
                var m      = ((pEnde.y-pStart.y)).toFixed(2);
                var b      = (pStart.y - m*abschnitt).toFixed(2);
                var y_wert = (m * x_wert + parseFloat(b)).toFixed(2);

                break;
            }
        }

        if(abschnitt < strecke.points.length){

            if(window.strecke_fahrrad_abschnitte.indexOf(i) != -1){

                if(window.strecke_fahrrad_abschnitte[i] != abschnitt){
                    // Abschnitt letzter merken
                    window.strecke_fahrrad_abschnitte[i] = abschnitt;

                    // Set Abschnitt to
                    // window.strecke_abschnitte[abschnitt];
                    $.ajax({
                        url: BASE_PATH + "abschnitt",
                        type: 'post',
                        data: {
                            fahrrad_id: window.fahrrad[i].id,
                            abschnitt_id: window.strecke_abschnitte[window.strecke_fahrrad_abschnitte[i]]
                        },
                        async: false,
                        success: function (data) { }
                    });
                }
            }

            if(typeof x_wert != "undefined"){
                window.fahrrad[i].x = parseFloat(x_wert);
                window.fahrrad[i].y = parseFloat(y_wert);
            }
        }else{
            if(typeof x_wert != "undefined"){
                window.fahrrad[i].x = parseFloat(strecke.points.length-1);
            }
        }
    }

    window.chart_strecke.redraw();
}

function getDataFromAPI(url, async, successHandler){
    $.ajax({
        url: BASE_PATH + url,
        type: 'get',
        async: async,
        success: successHandler
    });
}

function addFahrrad(id, color, modus, abschnitt_id){
    window.fahrrad.push(
        {
            id: id,
            color: color,
            gefahrene_strecke: 0.0,
            element: null,
            x: 0,
            y: 0,
            modus: modus,
            abschnitt_id: abschnitt_id
        }
    );

    window.chart_strecke.redraw();
}