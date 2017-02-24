<!--
 Hauptverantwortlich: Alice Domandl
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Deine Fahrradstatistik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
    <div>
        <h4>Hallo {{ $name }},</h4>
        <p>vielen Dank für die Teilnahme am Fahrradkino auf dem Hochschulinformationstag
            <br>der Westfälischen Hochschule Gelsenkirchen am {{ $datum }}.</p>
        <p>Deine Statistik:<br></p>
        <ul>
            <li>Modus: {{ $modus }}</li>
            <li>Hoechstgeschwindigkeit: {{ $hoechstGeschwindigkeit }} km/h</li>
            <li>Durchschnittsgeschwindigkeit: {{ $durchschnittsGeschwindigkeit }} km/h</li>
            <li>Gesamtleistung: {{ $gesamtleistung }} Watt</li>
            <li>Durchschnittsleistung: {{ $durchschnittsLeistung }} Watt</li>
            <li>Zurückgelegte Kilometer: {{ $strecke }} km</li>
            <li>Zurückgelegte Hoehenmeter: {{ $hoehenmeter }} km</li>
            <li>Fahrdauer: {{ $fahrdauer }}</li>
        </ul>
        <p>Mit freundlichen Grüßen<br></p>
        <p>Das Fahrradkino-Team<br></p>
        <p>Westfälische Hochschule<br>Gelsenkirchen Bocholt Recklinghausen<br>Neidenburger Str. 43<br>45897 Gelsenkirchen</p>
        <div class="logo">
            <img src="{{URL::asset('/img/whs_logo.png')}}" alt="Logo" class="pull-right">
        </div>
        <div class="softwaretitel-wrapper">
            <a class="softwaretitel" href="{{ url('/') }}">
                <span>SPIN WiSe 16/17 Fahrradergometer</span>
            </a>
        </div>
    </div>
</body>
</html>

