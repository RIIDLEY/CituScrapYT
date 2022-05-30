<?php

require_once "Utils/functions.php"; //Pour avoir les fonctions globals
require_once "Utils/data.php";//Pour avoir la liste des metadonnées
require_once "Models/Model.php"; //Inclusion du modèle
require_once "Controllers/Controller.php"; //Inclusion de la classe Controller
require('vendor/autoload.php');

$controllers = ["home","metadata","com","captions","graphreseau"]; //Liste des contrôleurs -- A RENSEIGNER
$controller_default = "metadata"; //Nom du contrôleur par défaut-- A RENSEIGNER

//On teste si le paramètre controller existe et correspond à un contrôleur de la liste $controllers
if (isset($_GET['controller']) and in_array($_GET['controller'], $controllers)) {
    $nom_controller = $_GET['controller'];
} else {
    $nom_controller = $controller_default;
}

//On détermine le nom de la classe du contrôleur
$nom_classe = 'Controller_' . $nom_controller;

//On détermine le nom du fichier contenant la définition du contrôleur
$nom_fichier = 'Controllers/' .  $nom_classe . '.php';

//Si le fichier existe
if (file_exists($nom_fichier)) {
    //On l'inclut et on instancie un objet de cette classe
    include_once $nom_fichier;
    $controller = new $nom_classe();
} else {
    exit("Error 404: not found!");
}
