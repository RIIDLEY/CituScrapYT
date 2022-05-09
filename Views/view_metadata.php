<?php
require('view_begin.php');

if (isset($_SESSION['token'])){
?>
        <h1>Collecteur de métadonnées</h1>
    <form class="form-inline" action = "?controller=metadata&action=recherche_mot_cle" method="post" style="display: inline-block;">
        <input type="text" name="name" size="50" placeholder="Mot clés"/>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>

    <h1>test</h1>
    <form class="form-inline" action = "?controller=metadata&action=test" method="post" style="display: inline-block;">
        <?php
        foreach ($metadata as $value) {
            echo "<input type='checkbox' name='listemetadata[]' id=$value value=$value >";
            echo "<label for='$value'>". str_replace("_"," ",$value) ."</label><br>";
        }

        ?>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>
    <?php

}else{
    ?>
    <h2>acces interdit</h2>
<?php
}
require('view_end.php');
?>
