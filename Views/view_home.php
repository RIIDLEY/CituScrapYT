<?php
require('view_begin.php');
?>

<form class="form-inline" action = "?controller=home&action=recherche_mot_cle" method="post" style="display: inline-block;">
    <input type="text" name="name" size="50" placeholder="Mot clés"/>
    <input type="submit" value="Chercher" class="btn btn-primary mb-2"/></form>

<?php
require('view_end.php');
?>
