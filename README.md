# Citu Scrap YT
***

L'objectif de ce projet est de réaliser une boite à outil permettant de faire de la collecte ainsi que de la visualisation de données disponible sur YouTube.

## Technologies utilisés

* PHP
* NodeJS
* [Boostrap](https://getbootstrap.com/)
* [Youtube DATA API](https://developers.google.com/youtube/v3)
* [SigmaJS](https://www.sigmajs.org/)/[Linkurious](https://github.com/Linkurious/linkurious.js/tree/develop)  

## Installation

Déposez le répertoire contenant le site dans votre dossier qui permet de le lancer avec Apache.
Mettez une clé API YouTube DATA API dans le fichier Utils/credentials.php
Voici quelques consignes en fonction du système d'exploitation où sera hébergé la boîte à outils:  

MacOS:  
Utilisez le logiciel MAMP. Modifiez le fichier Controllers/Controller_captions.php. Des instructions sont disponible à la ligne 66 de ce fichier. 

Linux:  
Modifiez les permissions du dossier /CSV afin que Apache puisse écrire dedans.

Windows:  
Il est recommandé d'utiliser le logiciel Xampp.

Vous pouvez maintenant utiliser la boite à outil CituScrapYT.  

## Collecteur de métadonnées

Cet outil permet de récupérer une sélection de métadonnées disponibles sur des vidéos YouTube. Pour cela, l'utilisateur doit effectuer une recherche avec des mots-clés ainsi que sélectionner les métadonnées à collecter ainsi que la méthode de trie des vidéos à traiter. Ces métadonnées sont ensuite enregistrées dans un fichier CSV puis envoyé à l'utilisateur courant.

## Collecteur de commentaires

Cet outil permet de collecter les commentaires (et les réponses des commentaires) ainsi que les métadonnées qui y sont liées d'une vidéo YouTube.

## Collecteur de sous-titres

Cet outil permet de récupérer les sous-titres sous plusieurs langues (en fonction des disponibilités) d'une vidéo YouTubes. 

## Visualisation : Graphique en réseau

Un outil de visualisation est directement disponible dans la boîte à outil. Celui ci permet de de visualiser les differentes liaisons entre des vidéos YouTube et leurs tags sous le format d'un graphique en réseau.
