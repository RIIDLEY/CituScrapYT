<?php

class Controller_metadata extends Controller{

  public function action_default(){
    $this->render('metadata');
  }

  public function action_recherche_mot_cle()
  {
    if (isset($_POST['name']) and !preg_match("#^\s*$#", $_POST['name']) and isset($_POST['listemetadata']) and isset($_POST['nbdata'])) {
      try {
        include 'Utils/data.php';
        include 'Utils/credentials.php';
        $client = new Google_Client();
        $client->setDeveloperKey($keyAPI);
        $youtube = new Google_Service_Youtube($client);

        $filename = 'CSV/MetaData_'.str_replace(" ","_",$_POST['name']).'.csv';//prepare le fichier
        $toCSV = array(
            $_POST['listemetadata']
        );//met le titre des colonnes
        $token = "";
        $i=0;

        if (file_exists($filename)) {//supprime le fichier s'il existe
          unlink($filename);
          $fp = fopen($filename, 'w');
        } else {
          $fp = fopen($filename, 'w');
        }

        do{

          $queryParams=['q' => $_POST['name'], 'order' => $_POST['radio'], 'maxResults' => 100, 'type' => 'video'];

          if(!empty($token)){//s'il y'a un token pour la page suivante
            $arr2 = array('pageToken' => $token);
            $queryParams = $queryParams + $arr2;//le met dans le tableau de parametre
          }

        $listSearch = $youtube->search->listSearch('id,snippet', $queryParams);//fait la recherche

        $token = $listSearch->getNextPageToken();//genere le token pour la page suivante

        foreach ($listSearch["items"] as $listSearchResp) {
          $tmp = array();
          $listVideosResp = $youtube->videos->listVideos('snippet,statistics', ['id' => $listSearchResp['id']['videoId']]);//Recupere plus d'information sur chaque vidéo
          foreach ($_POST['listemetadata'] as $value) {//get les informations en fonction de ce que l'utilisateur a selectionné
            switch ($value) {
              case "Titre":
                array_push($tmp, getTitre($listSearchResp));
                break;
              case "Lien":
                array_push($tmp, getLien($listSearchResp));
                break;
              case "Description":
                array_push($tmp, getDescription($listVideosResp));
                break;
              case "Tags":
                array_push($tmp, getTags($listVideosResp));
                break;
              case "Date":
                array_push($tmp, getDateUpload($listSearchResp));
                break;
              case "Nom_de_la_chaine":
                array_push($tmp, getNameChannel($listSearchResp['snippet']['channelId'], $youtube));
                break;
              case "Nombre_de_like":
                array_push($tmp, getNbLike($listVideosResp));
                break;
              case "Nombre_de_vue":
                array_push($tmp, getNbViews($listVideosResp));
                break;
            }
          }
          array_push($toCSV, $tmp);
        }
        $i++;
        }while($i<$_POST['nbdata']/50);//boucle tant qu'il n'a pas atteint le nombre souhaité par l'utilisateur

      } catch (Google_Service_Exception $e) {
        echo "<script>alert('Une erreur est survenu')</script>";
        $this->render("metadata");
      } catch (GuzzleHttp_Exception_RequestException $e) {
        echo "<script>alert(\"Une erreur de co est survenu\")</script>";
        $this->render("metadata");
      } catch (GuzzleHttp_Exception_ConnectException $e) {
        echo "<script>alert(\"Une erreur de co est survenu\")</script>";
        $this->render("metadata");
      }

      foreach ($toCSV as $fields) {//set le tableau généré dans le fichier CSV
        fputcsv($fp, $fields);
      }

      fclose($fp);

      echo '<iframe width="1" height="1" frameborder="0" src="'.$filename.'"></iframe>';//envoie le fichier
      $this->render("metadata");

    }else{
      echo "<script>alert(\"Il manque des informations pour effectuer la collecte de données.\")</script>";
      $this->render("metadata");
    }


  }


}

?>
