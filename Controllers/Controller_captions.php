<?php

class Controller_captions extends Controller{

  public function action_default(){
    $this->render("captions");
  }

  public function action_captions(){

     if (isset($_POST['name']) and !preg_match("#^\s*$#", $_POST['name'])) {
       try {

         $video_url = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $_POST['name']);//Verifie si la vidéo existe
         if (!$video_url) {
           echo "<script>alert(\"Identifiant incorrect.\")</script>";
           $this->render("captions");//si elle n'existe pas, met une pop up et renvoie vers la page initial
         }
         include 'Utils/credentials.php';
         $client = new Google_Client();
         $client->setDeveloperKey($keyAPI);//set la clée API

         $youtube = new Google_Service_YouTube($client);//genere un objet api google
         $tmp = array();
         $response = $youtube->captions->listCaptions('snippet', $_POST['name']);//recupere la liste des sous titres disponible

         foreach ($response['items'] as $caption_lang){//met toute les langues dispo dans un tableau qui sera envoyé à la vue
           array_push($tmp,$caption_lang['snippet']["language"]);
         }


         $tmp = array_unique($tmp);//retire les doublons

         $tmpExec = 'node Captions.js ' . $_POST['name'] ." ". $tmp[0];
         $command = escapeshellcmd($tmpExec);//prepare la commande
         $output = shell_exec($command);//execute la commande

         if($output==403){//Verifie si les sous-titres ne sont pas desactivé
           echo "<script>alert(\"Les sous-titres sont desactivé sur cette vidéo.\")</script>";
           $this->render("captions");
         }else{
           $this->render('captions',["tablang"=>$tmp,"idVideo"=>$_POST['name'],"DataVideo"=>$youtube->videos->listVideos('snippet,contentDetails,statistics',["id"=>$_POST['name']])]);//envoie à la vue avec un tableau d'information
         }


       } catch (Google_Service_Exception $e) {
         echo "<script>alert('Une erreur est survenue au niveau de l\'API Youtube.')</script>";
         $this->render("captions");
       } catch (GuzzleHttp_Exception_RequestException $e) {
         echo "<script>alert(\"Une erreur de connexion est survenu.\")</script>";
         $this->render("captions");
       } catch (GuzzleHttp_Exception_ConnectException $e) {
         echo "<script>alert(\"Une erreur de connexion est survenu.\")</script>";
         $this->render("captions");
       }

     }else{
       echo "<script>alert(\"Il manque des informations pour effectuer la collecte de données.\")</script>";
       $this->render("captions");
     }

  }

  public function action_download(){
    if (isset($_GET['lang']) and isset($_GET['IdVideo'])){
      $tmp = 'node Captions.js ' . $_GET['IdVideo'] ." ". $_GET['lang'];
      $command = escapeshellcmd($tmp);//prepare la commande
      $output = shell_exec($command);//execute la commande

      if (trim($output)=="Done"){//si success
        $filename = 'CSV/Captions_'.$_GET['IdVideo'].'_'.$_GET['lang'].'.csv';
        header("Content-disposition: attachment;filename=$filename");
        header('Content-Length: ' . filesize($filename));
        readfile($filename);//envoie le fichier au client
      }else{
        echo "<script>alert(\"Une erreur est survenue. Vérifiez votre connexion.\")</script>";
        $this->render("captions");
      }

    }else{
      echo "<script>alert(\"Une erreur est survenu.\")</script>";
      $this->render("captions");
    }

  }

}

?>
