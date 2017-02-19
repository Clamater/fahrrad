<div class="col-md-6">
    <!-- Panel der Fahrertabelle -->
    <div class="panel panel-default" id="panelAdmin">

        <!-- Header -->
        <div class="panel-heading" id="panelHeadingAdmin">
            <!-- Titel -->
            <h3 class="panel-title pull-left" id="panelTitelAdmin">Fahrer</h3>
            <!-- Navigation -->
            <div class="row col-md-12" id="fahrerNav">
                <button type="button" class="btn btn-default pull-right" id="btnHilfeTabelle">Hilfe</button>
                <button type="button" class="btn btn-default pull-right" id="btnAddFahrer">
                    <span class="glyphicon glyphicon-plus"></span>
                    Fahrer hinzufügen
                </button>
            </div>
            <span class="clearfix"></span>
        </div>

        <!-- Body  -->
        <div class="panel-body" id="panelBodyAdmin">
            <!-- Tabelle -->
            <form action="{{ url("fahrer") }}" method="PUT">
                <table class="table table-striped table-bordered table-hover" id="userTable">
                    <!-- Tabellenhead -->
                    <thead>
                        <tr>
                            <th class="no-sort"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gewicht(kg)</th>
                            <th>Größe(m)</th>
                            <th class="no-sort">Betriebsmodus</th>
                            <th class="no-sort"></th>
                        </tr>
                    </thead>
                    <!-- Tabellenbody -->
                    <tbody>
                        @foreach($fahrer as $f)
                            <tr draggable="true" id="{{$f->id}}" ondragstart="drag(event)">
                                <th id="th_fahrer_id">
                                    <fieldset>
                                        <input type="radio" name="radio_fahrer_id" class="radio-fahrer-id" value="{{$f->id}}">
                                    </fieldset>
                                </th>
                                <td id="name">{{$f->name}}</td>
                                <td id="email">{{$f->email}}</td>
                                <td id="gewicht">{{$f->gewicht}}</td>
                                <td id="groesse">{{$f->groesse}}</td>
                                <th id="betriebsmodus">
                                    <select class="form-control" id="betriebsmodusAuswahlFahrer">
                                        @foreach($modi as $modus)
                                            @if($f->modus_id == $modus->id)
                                                <option value="{{ $modus->id }}" selected>{{ $modus->name }}</option>
                                            @else
                                                <option value="{{ $modus->id }}">{{ $modus->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <div class="btn btn-default btnDelete">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="clear"></div>
</div>
