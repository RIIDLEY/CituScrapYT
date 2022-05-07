<?php

function remove_emoji($string)
{
  // Match Enclosed Alphanumeric Supplement
  $regex_alphanumeric = '/[\x{1F100}-\x{1F1FF}]/u';
  $clear_string = preg_replace($regex_alphanumeric, '', $string);

  // Match Miscellaneous Symbols and Pictographs
  $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
  $clear_string = preg_replace($regex_symbols, '', $clear_string);

  // Match Emoticons
  $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
  $clear_string = preg_replace($regex_emoticons, '', $clear_string);

  // Match Transport And Map Symbols
  $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
  $clear_string = preg_replace($regex_transport, '', $clear_string);

  // Match Supplemental Symbols and Pictographs
  $regex_supplemental = '/[\x{1F900}-\x{1F9FF}]/u';
  $clear_string = preg_replace($regex_supplemental, '', $clear_string);

  // Match Miscellaneous Symbols
  $regex_misc = '/[\x{2600}-\x{26FF}]/u';
  $clear_string = preg_replace($regex_misc, '', $clear_string);

  // Match Dingbats
  $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
  $clear_string = preg_replace($regex_dingbats, '', $clear_string);

  return $clear_string;
}

class Controller_home extends Controller{

  public function action_default(){
    $this->render('home');
  }


  public function action_recherche_mot_cle(){
    //echo "<script>alert(\"coucou\")</script>";
    if(isset($_POST['name']) and !preg_match("#^\s*$#",$_POST['name'])) {
      try {
      $client = new Google_Client();
      $client->setDeveloperKey('AIzaSyBA_E1glTlK44wkkvxRYaWa-5y40OuLD2U');
      $client->setScopes([
          'https://www.googleapis.com/auth/youtube.force-ssl',
      ]);

      $youtube = new Google_Service_Youtube($client);
      $response = $youtube->search->listSearch('id,snippet', ['q'=>$_POST['name'], 'order'=>'relevance', 'maxResults'=>100, 'type'=>'video']);

      $toCSV = array (
          array('Titre', 'Lien', 'Description','Tags'),
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


        foreach ($toCSV as $fields) {
          fputcsv($fp, $fields);
        }

      }
      }catch (Google_Service_Exception $e){

      }

      fclose($fp);
      /*header("Content-type: application/octet-stream");
      header("Content-disposition: attachment;filename=$fp");*/
    }
      $this->render('home');

  }
  



}

?>
