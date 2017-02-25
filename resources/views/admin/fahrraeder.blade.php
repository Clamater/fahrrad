<!--
 Hauptverantwortlich: Clara Terbeck
-->
<div class="col-md-6">
    <!-- Erzeugen der einzelnen Fahrradpanels in einer Schleife -->
    @foreach ($fahrraeder as $fahrrad)
        @include("admin.fahrrad")
    @endforeach
</div>