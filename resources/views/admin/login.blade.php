<!--
 Hauptverantwortlich: Clara Terbeck
-->
@extends("layouts.app")

@section('content')
    <div class="col-md-4 col-md-offset-4">
        <!-- Panel Anmeldung -->
        <div class="panel panel-default" id="panelAdmin">
            <!-- Panelhead -->
            <div class="panel-heading" id="panelHeadingAdmin">
                <!-- Paneltitel -->
                <h3 class="panel-title" id="panelTitelAdmin">Anmeldung Administrationsbereich
                </h3>
                <span class="clearfix"></span>
            </div>
            <!-- Panelbody -->
            <form class="panel-body form-inline" id="panelBodyAdmin" action="{{ url("admin/login") }}" method="post">
                <!-- Fehlermeldung bei falschem Passwort -->
                @if ($errors->has('password'))
                    <div class="alertFalschesPasswort">
                        <strong>{{ $errors->first('password') }}</strong>
                    </div>
                 <!-- Formular -->
                @endif
                    <div class="form-group">
                    <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Admin-Passwort">
                    <input class="btn btn-default" id="btnLogin" type="submit" value="Login">
                    {{ csrf_field() }}
                </div>
            </form>
        </div>
    </div>
@endsection


