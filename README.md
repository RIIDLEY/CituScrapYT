# Citu Scrap YT
***

L'objectif de ce projet est de réaliser une boite à outil permettant de faire de la collecte ainsi que de la visualisation de données disponible sur YouTube.

## Technologies utilisés

* PHP
* JS/nodeJS
* Boostrap
* Youtube DATA API
* SigmaJS/Linkurious  

## Comment l'installer

Mettez le dossier contenant le site dans votre dossier qui permet de le lancer avec Apache.  
Allez sur votre navigateur Web, puis à l'adresse localhost/CituScrapYT-master pour avoir accès au systeme que j'ai réalisé.  

## Collecteur de métadonnées

Un système de pagination a été installé afin de naviguer entre les images présentes sur le serveur. Ce système permet d'affiche 4 images par page.

## Collecteur de commentaires

Un utilisateur a la possibilité de téléverser une image. Cette image sera ensuite téléversée vers le dossier de stockage du serveur et référencée sur la base de données.
Une image ne peut pas être téléversé plus d'une fois.

## Collecteur de sous-titres

Un bouton "Scan" est présent sur le site web. Il permet de lire l’ensemble des images dans le dossier et sous dossiers possibles.
Ces images sont ensuite téléversées vers le dossier de stockage du serveur et référencées sur la base de données.

## Visualisation : Graphique en réseau

Un système d'administration a été ajouté. 
Un membre de l'administration peut se créer un compte. Le mot de passé est hashé et les informations sont envoyé à la base de données.
Par la suite il peut se connecter et supprimer des images.
