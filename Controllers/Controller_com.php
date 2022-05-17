<?php
session_start();

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

        $video_url = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v='.$_POST['name']);
        if(!$video_url) {
          echo "<script>alert(\"Id incorrect\")</script>";
          $this->render("com");
        }

        include 'Utils/credentials.php';
        $client = new Google_Client();
        $client->setDeveloperKey($keyAPI);

        $youtube = new Google_Service_Youtube($client);

        $filename = 'CSV/fileComm.csv';

        if (file_exists($filename)) {
          unlink($filename);
          $fp = fopen($filename, 'w');
        } else {
          $fp = fopen($filename, 'w');
        }

        $toCSV = getComm($_POST['name'],$_POST['nbdata'],$_POST['radio'],$youtube);


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
      $this->render("com");
    }

  }

}

?>
