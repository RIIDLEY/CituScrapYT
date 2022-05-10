<?php
session_start();

class Controller_metadata extends Controller{

  public function action_default(){
    $this->render('metadata');
  }

  public function action_recherche_mot_cle()
  {
    if (isset($_POST['name']) and !preg_match("#^\s*$#", $_POST['name']) and isset($_POST['listemetadata']) and isset($_POST['nbdata'])) {
      try {

        $client = new Google_Client();
        $client = new Google_Client();
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.force-ssl',
        ]);

        $youtube = new Google_Service_Youtube($client);
        $client->setAccessToken($_SESSION['token']);

        include 'Utils/data.php';
        $filename = 'file.csv';
        $toCSV = array(
            $_POST['listemetadata']
        );

        if (file_exists($filename)) {
          unlink($filename);
          $fp = fopen($filename, 'w');
        } else {
          $fp = fopen($filename, 'w');
        }

        for ($i=0; $i<$_POST['nbdata']/50; $i++){
        $listSearch = $youtube->search->listSearch('id,snippet', ['q' => $_POST['name'], 'order' => $_POST['radio'], 'maxResults' => 100, 'type' => 'video']);

        foreach ($listSearch["items"] as $listSearchResp) {
          $tmp = array();
          $listVideosResp = $youtube->videos->listVideos('snippet,statistics', ['id' => $listSearchResp['id']['videoId']]);
          foreach ($_POST['listemetadata'] as $value) {
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
                print("\n");
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
        }


      } catch (Google_Service_Exception $e) {
        echo "<script>alert('Une erreur est survenu:$e')</script>";
      } catch (GuzzleHttp_Exception_RequestException $e) {
        echo "<script>alert(\"Une erreur de co est survenu\")</script>";
      }

      foreach ($toCSV as $fields) {
        fputcsv($fp, $fields);
      }

      fclose($fp);

      header("Content-disposition: attachment;filename=$filename");
      header('Content-Length: ' . filesize($filename));
      readfile($filename);

    }else{
      echo "<script>alert(\"Il manque des données pour effectuer le scrapping\")</script>";
      $this->render("metadata");
    }


  }


}

?>
