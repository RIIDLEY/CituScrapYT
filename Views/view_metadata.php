<?php
require('view_begin.php');

if (isset($_SESSION['token'])){
?>
    <a href="?controller=home&action=home" style="text-decoration: none;">Back</a>
    <div class="container">
    <h1>Collecteur de metadonées</h1>
    <form class="form-inline" action = "?controller=metadata&action=recherche_mot_cle" method="post">
        <input type="text" name="name" size="50" placeholder="Mot clés"/>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/>
        <br>
        <label>Metadata : </label> <br>
        <?php
        include 'Utils/data.php';
        foreach ($metadata as $value) {
            echo "<input type='checkbox' class='form-check-input' name='listemetadata[]' id=$value value=$value >";
            echo "<label class='form-check-label' for='$value'>". str_replace("_"," ",$value) ."</label><br>";
        }

        ?>

        <label>Trié par : </label> <br>
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

        <label>Nb Data par : </label> <br>
        <select name="nbdata">
            <option value="">--Please choose an option--</option>

            <?php

            foreach ($listeNbData as $value) {
                echo "<option value='$value'>$value</option>";
            }

            ?>

        </form>
    </div>
    <?php

}else{
    ?>
    <h2>acces interdit</h2>
<?php
}
require('view_end.php');
?>
