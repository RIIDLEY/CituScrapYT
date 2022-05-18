<?php
require('view_begin.php');
?>
<script>
    var element = document.getElementById("cap");//Modifie la navbar en fonction de la page actuel
    element.classList.add("active");

    function chargement() {//Set le bouton de chargement
        var element = document.getElementById("buttonSubmit");
        element.classList.add("displayNone");

        var element = document.getElementById("buttonLoad");
        element.classList.remove("displayNone");
    }

</script>

<div class="container grandeDivData">
    <h1>Collecteur de sous-titre</h1>
    <form class="form-inline" action = "?controller=captions&action=captions" method="post">
        <input type="text" name="name" size="25%" placeholder="Identifiant Vidéo Youtube"/><br>
        <p> L'identifiant d'une vidéo peut être trouvé dans son URL : <span class="grey">https://www.youtube.com/watch?v=</span><strong>nP-nMZpLM1A</strong></p>
        <input type="submit" value="Collecter" id="buttonSubmit" class="btn btn-primary mb-2" onclick="chargement()"/>

        <button class="btn btn-primary displayNone" id="buttonLoad" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="sr-only">Chargement</span>
        </button>

    </form>

<?php

if (isset($tablang) and isset($idVideo) and isset($DataVideo)){
    echo "<hr>";

    foreach ($DataVideo['items'] as $detail){//affiche les informations de la vidéo qui vas etre traité
        echo "<u><h4>Vidéo sélectionnée :</h4></u>";
        echo "<h5>".$detail['snippet']['title']."</h5>";
        echo "<img src='".$detail['snippet']['thumbnails']['medium']['url']."' wigth='100%'>";
    }
    echo "<u><h4>Veuillez sélectionner une langue :</h4></u>";
    foreach ($tablang as $value) {//affiche les langues disponibles
        echo '<a class="btn btn-primary" style="margin:1%;" href="?controller=captions&action=download&lang='.$value.'&IdVideo='.$idVideo.'">'.$value.'</a>';
    }
}
?>
</div>
<?php
require('view_end.php');
?>
