<?php
session_start();

class Controller_metadata extends Controller{

  public function action_default(){
    $this->render('metadata');
  }

  public function action_recherche_mot_cle(){
    if(isset($_POST['name']) and !preg_match("#^\s*$#",$_POST['name'])) {
      try {
      $client = new Google_Client();
      $client->setScopes([
          'https://www.googleapis.com/auth/youtube.force-ssl',
      ]);

      $youtube = new Google_Service_Youtube($client);
      $client->setAccessToken($_SESSION['token']);
      $response = $youtube->search->listSearch('id,snippet', ['q'=>$_POST['name'], 'order'=>'relevance', 'maxResults'=>5, 'type'=>'video']);
        include 'Utils/data.php';
      $toCSV = array (
          $metadata
      );

      $fp = fopen('file.csv', 'w');



      foreach($response["items"] as $videoList){

        $tmp = array();
        array_push( $tmp, $videoList['snippet']['title']);//recupere le titre
        $link = "https://www.youtube.com/watch?v=". $videoList['id']['videoId'];
        array_push($tmp, $link);
        $DataVideo = $youtube->videos->listVideos('snippet', ['id'=>$videoList['id']['videoId']]);

        foreach ($DataVideo['items'] as $detail){
          array_push($tmp, str_replace("\n",' ',remove_emoji($detail['snippet']['description'])));
          if (!empty($detail['snippet']['tags'])){
            array_push($tmp,implode(" ",$detail['snippet']['tags']));
          }
        }

        array_push($toCSV, $tmp);
      }
        foreach ($toCSV as $fields) {
          fputcsv($fp, $fields);
        }
      }catch (Google_Service_Exception $e){

      }

      fclose($fp);
      //$this->render('home',['filename'=>$fp]);
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.basename('file.csv').'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize('file.csv'));
      readfile('file.csv');
    }else{
      $this->render('home');
    }
  }

  public function action_test(){

    $key = 'AIzaSyBA_E1glTlK44wkkvxRYaWa-5y40OuLD2U';

    $client = new Google_Client();
    $client->setDeveloperKey($key);

    $youtube = new Google_Service_Youtube($client);

    $response = $youtube->search->listSearch('id,snippet', ['q'=>'raccoon', 'order'=>'relevance', 'maxResults'=>1, 'type'=>'video']);
    /*
    $toCSV = array (
        $metadata
    );

    $fp = fopen('file.csv', 'w');*/

    foreach($response["items"] as $videoList){
      $DataVideo = $youtube->videos->listVideos('snippet', ['id'=>$videoList['id']['videoId']]);
        foreach ($_POST['listemetadata'] as $value){
        switch ($value){
          case "Titre":
            print(getTitre($videoList));
         case "Lien":
            print(getLien($videoList));/*
          case "Description":
            print(getTitre($videoList));
          case "Tags":
            print(getTitre($videoList));
          case "Date":
            print(getTitre($videoList));
          case "Nom_de_la_chaine":
            print(getTitre($videoList));
          case "Nombre_de_like":
            print(getTitre($videoList));
          case "Nombre_de_vue":
            print(getTitre($videoList));*/
        }
        }
    }
    $this->render('metadata');
  }


}

?>
