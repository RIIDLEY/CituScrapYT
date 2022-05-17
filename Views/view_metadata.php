<?php
require('view_begin.php');

?>
<script>
    var element = document.getElementById("meta");
    element.classList.add("active");


    function loading() {
        var element = document.getElementById("myDiv");
        element.classList.add("loader");
    }
</script>

    <div class="grandeDivData">
    <h1>Collecteur de metadonées</h1>
    <form class="form-inline" action = "?controller=metadata&action=recherche_mot_cle" method="post">
        <input type="text" name="name" size="50" placeholder="Mot clés"/>

        <div class="option">

        <div class="divTypeMeta">
        <u><h4>Metadata :</h4></u>
        <?php
        include 'Utils/data.php';
        foreach ($metadata as $value) {
            echo "<input type='checkbox' class='form-check-input' name='listemetadata[]' id=$value value=$value >";
            echo "<label class='form-check-label' for='$value'>". str_replace("_"," ",$value) ."</label><br>";
        }

        ?>
        </div>


        <div class="divSort">
        <u><h4>Trié par :</h4></u>
        <?php
        foreach ($order as $value) {
            if ($value=="relevance"){
                $etat = "checked";
            }else{
                $etat = "";
            }
            echo "<input type='radio' class='form-check-input' name='radio' id=$value value=$value $etat>";
            echo "<label class='form-check-label' for='$value'>". str_replace("_"," ",$value) ."</label><br>";
        }

        ?>
        </div>

         <div class="divNbData">
         <u><h4>Nombre de données :</h4></u>
        <select name="nbdata">
            <option value="">-</option>

            <?php

            foreach ($listeNbData as $value) {
                echo "<option value='$value'>$value</option>";
            }

            ?>
        </select>
         </div>
            <br>
        </div>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/>
        </form>
    </div>

<button class="btn btn-primary" type="button" disabled>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span class="sr-only">Loading...</span>
</button>

<?php

require('view_end.php');
?>
