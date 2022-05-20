<?php

class Controller_com extends Controller
{

  public function action_default()
  {
    $this->render('com');
  }

  public function action_recherche_com()
  {
    if (isset($_POST['name']) and !preg_match("#^\s*$#", $_POST['name']) and isset($_POST['nbdata'])) {
      try {

        $video_url = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v='.$_POST['name']);//Verifie si la vidéo existe
        if(!$video_url) {
          echo "<script>alert(\"Identifiant incorrect.\")</script>";
          $this->render("com");//si elle n'existe pas, met une pop up et renvoie vers la page initial
        }

        include 'Utils/credentials.php';
        $client = new Google_Client();
        $client->setDeveloperKey($keyAPI);//set la clée API

        $youtube = new Google_Service_Youtube($client);//genere un objet api google

        $filename = 'CSV/Comm_'.$_POST['name'].'.csv';//prepare le fichier

        if (file_exists($filename)) {//supprime le fichier s'il existe
          unlink($filename);
          $fp = fopen($filename, 'w');
        } else {
          $fp = fopen($filename, 'w');
        }

        $toCSV = getComm($_POST['name'],$_POST['nbdata'],$_POST['radio'],$youtube);//appel la fonction qui recupere les commentaires


      } catch (Google_Service_Exception $e) {
        if($e->getCode()==403){
          echo "<script>alert('Les commentaires de la vidéo sont desactivé')</script>";
        }else{
          echo "<script>alert('Une erreur est survenue au niveau de l\'API Youtube')</script>";
        }
        $this->render("com");
      } catch (GuzzleHttp_Exception_RequestException $e) {
        echo "<script>alert(\"Une erreur de connexion est survenu\")</script>";
        $this->render("com");
      } catch (GuzzleHttp_Exception_ConnectException $e) {
        echo "<script>alert(\"Une erreur de connexion est survenu\")</script>";
        $this->render("com");
      }

     foreach ($toCSV as $fields) {//set le tableau généré dans le fichier CSV
        fputcsv($fp, $fields);
      }

      fclose($fp);

      echo '<iframe width="1" height="1" frameborder="0" src="'.$filename.'"></iframe>';//envoie le fichier
      $this->render("com");//Affiche la vue

    }else{
      echo "<script>alert(\"Il manque des informations pour effectuer la collecte de données.\")</script>";
      $this->render("com");
    }

  }

}

?>
