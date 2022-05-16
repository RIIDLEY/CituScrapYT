<?php
require('view_begin.php');

?>
<a href="?controller=home&action=home" style="text-decoration: none;">Back</a>
<div class="container">
    <h1>Collecteur de sous-titre</h1>
    <form class="form-inline" action = "?controller=captions&action=captions" method="post">
        <input type="text" name="name" size="50" placeholder="Id vidéo"/>
        <input type="submit" value="Chercher" class="btn btn-primary mb-2"/><br>
    </form>
</div>

<?php

if (isset($tablang) and isset($idVideo)){

    foreach ($tablang as $value) {
        echo '<a href="?controller=captions&action=download&lang='.$value.'&IdVideo='.$idVideo.'">'.$value.'</a><br>';
    }
}

require('view_end.php');
?>
