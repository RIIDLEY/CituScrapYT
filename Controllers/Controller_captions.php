<?php

class Controller_captions extends Controller{

  public function action_default(){
    $this->render("captions");
  }

  public function action_captions(){

     if (isset($_POST['name']) and !preg_match("#^\s*$#", $_POST['name'])) {
       try {

         $video_url = @file_get_contents('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $_POST['name']);
         if (!$video_url) {
           echo "<script>alert(\"Id incorrect.\")</script>";
           $this->render("captions");
         }

         $key = 'AIzaSyBA_E1glTlK44wkkvxRYaWa-5y40OuLD2U';

         $client = new Google_Client();
         $client->setDeveloperKey($key);

         $youtube = new Google_Service_Youtube($client);
          $tmp = array();
         $response = $youtube->captions->listCaptions('snippet', $_POST['name']);

         foreach ($response['items'] as $caption_lang){
           array_push($tmp,$caption_lang['snippet']["language"]);
         }

         $tmp = array_unique($tmp);
         $this->render('captions',["tablang"=>$tmp,"idVideo"=>$_POST['name']]);
       } catch (Google_Service_Exception $e) {
         echo "<script>alert('Une erreur est survenu:$e')</script>";
       } catch (GuzzleHttp_Exception_RequestException $e) {
         echo "<script>alert(\"Une erreur de co est survenu\")</script>";
       }

     }

  }

  public function action_download(){
    if (isset($_GET['lang']) and isset($_GET['IdVideo']))

    $tmp = 'node Captions.js ' . $_GET['IdVideo'] ." ". $_GET['lang'];
    $command = escapeshellcmd($tmp);
    $output = shell_exec($command);

    if (trim($output)=="Done"){
      header("Content-disposition: attachment;filename=captions.csv");
      header('Content-Length: ' . filesize("captions.csv"));
      readfile("captions.csv");
    }else{
      echo "<script>alert(\"Une erreure est survenu.\")</script>";
    }


    //$this->render("captions");

  }

}

?>
