<?php
require('view_begin.php');

if (isset($_GET['code'])){
    $client->authenticate($_GET['code']);

    $_SESSION['token'] = $client->getAccessToken();

    header('Location: http://localhost/Stage');
    die();
}

if (isset($_SESSION['token'])){
    $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()){
?>
    <a href="?controller=metadata&action=metadata" style="text-decoration: none;"><span class="circle">Metadata</span></a>
    <a href="?controller=com&action=com" style="text-decoration: none;"><span class="circle">Commentaire</span></a>
    <a href="" style="text-decoration: none;"><span class="circle">Transcription</span></a>
    <a href="?controller=home&action=deco" style="text-decoration: none;"><span class="circle">Deco</span></a>
    <?php

}else{

    ?>
    <h2>acces interdit</h2>

    <p>Ici <a href="<?= $client->createAuthUrl(); ?>">autorisation</a> </p>

    <?php
}
require('view_end.php');
?>
