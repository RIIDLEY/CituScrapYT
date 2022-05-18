<?php
require('view_begin.php');
include 'Utils/data.php';
?>
<script>
    var element = document.getElementById("com");//Modifie la navbar en fonction de la page actuel
    element.classList.add("active");

    function chargement() {//Set le bouton de chargement
        var element = document.getElementById("buttonSubmit");
        element.classList.add("displayNone");

        var element = document.getElementById("buttonLoad");
        element.classList.remove("displayNone");
    }

</script>
    <div class="container grandeDivData">
    <h1>Collecteur de commentaires</h1>
    <form class="form-inline" action = "?controller=com&action=recherche_com" method="post">
        <input type="text" name="name" size="25%" placeholder="Identifiant Vidéo Youtube"/>
        <p> L'identifiant d'une vidéo peut être trouvé dans son URL : <span class="grey">https://www.youtube.com/watch?v=</span><strong>nP-nMZpLM1A</strong></p>

        <div class=" rowTools">
            <div class="col">
            <u><h4>Trié par :</h4></u>
        <?php
        foreach ($ordercom as $value) {//Affiche dynamiquement la liste de methode de trie disponible
            if ($value=="relevance"){//selectionne par defaut la methode par relevance
                $etat = "checked";
            }else{
                $etat = "";
            }
            echo "<input type='radio' class='form-check-input' name='radio' id=$value value=$value $etat> ";
            echo "<label class='form-check-label' for='$value'>". str_replace("_"," ",$value) ."</label><br>";
        }

        ?>
            </div>
        </div>

        <div class="row rowTools2">
            <div class="col">
                <u><h4>Nombre de commentaire :</h4></u>
        <select name="nbdata">
            <option value="">-</option>

            <?php

            foreach ($listeNbDataCom as $value) {//Fait une liste dynamiquement du nombre de données que l'utilisateur peut traiter
                echo "<option value='$value'>$value</option>";
            }

            ?>
        </select>
        </div>
        </div>

        <input type="submit" id="buttonSubmit" value="Collecter" class="btn btn-primary mb-2" onclick="chargement()"/>

        <button class="btn btn-primary displayNone" id="buttonLoad" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="sr-only">Chargement</span>
        </button>
    </form>
    </div>
<?php
require('view_end.php');
?>
