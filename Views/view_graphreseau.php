<?php
require('view_begin.php');
include 'Utils/data.php';
include 'Utils/import_sigmaJS.php';
?>
<script>
    var element = document.getElementById("GraphRe");//Modifie la navbar en fonction de la page actuel
    element.classList.add("active");

</script>

<div class="container" >
    <div class="row grandeDivData">
        <div class="col">
    <div id="form" class="custom-file">
        <input type="file" id="uploadfile" class="custom-file-input" accept=".csv" aria-label="File browser example">
        <button id="uploadconfirm" class="btn btn-light">Upload</button>
        <br>
        <u><h4>Méthode de rendu :</h4></u>
            <input class="form-check-input" type="radio" name="rendu" id="flexRadioDefault1" value="fruchtermanReingold" checked>
            <label class="form-check-label" for="flexRadioDefault1">Fruchterman Reingold</label><br>

            <input class="form-check-input" type="radio" name="rendu" id="flexRadioDefault1" value="configForceLink">
            <label class="form-check-label" for="flexRadioDefault1">Force Link</label>

        <input class="form-check-input" type="radio" name="rendu" id="flexRadioDefault1" value="ForceAtlas2">
        <label class="form-check-label" for="flexRadioDefault1">Force Atlas 2</label>
    </div>

    <span id="layout-notification" class="displayNone">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only"> Chargement</span>
    </span>

    <span id="reset" class="displayNone">
            <button id="download" class="btn btn-primary">Télécharger en .GEXF</button>
           <a href="?controller=graphreseau" class="btn btn-primary" role="button">Nouveau graphique</a>
    </span>
        </div>
    </div>
    <div class="row">

        <div id="tools" class="col-md-auto divStyle displayNone">
            <h4><u>Outils :</u></h4>
                <button id="degreeEntrant" class="btn btn-primary" style="margin: 10px">Degrés entrant</button><br>
                <button id="degreeSortant" class="btn btn-primary" style="margin: 10px">Degrés sortant</button><br>
                <button id="textEtat" class="btn btn-primary" style="margin: 10px">Afficher les labels</button><br>
            <button id="resetdegree" class="btn btn-primary" style="margin: 10px">Reinitialiser</button>
        </div>


        <div class="col">
    <div id='sigma-container'></div>
    </div>

    </div>

</div>
<script type="text/javascript" src="Assets/Script/GraphReseau.js"></script>

<?php
require('view_end.php');
?>
