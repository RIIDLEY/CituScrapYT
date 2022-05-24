<?php


/**
 * Fonction échappant les caractères html dans $message
 * @param  string $message chaîne à échapper
 * @return string          chaîne échappée
 */
function e($message)
{
    return htmlspecialchars($message, ENT_QUOTES);
}

function remove_emoji($string)//fonction qui supprime les emojis d'une chaine de caractere
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

function getTitre($listSearchResp){
    return $listSearchResp['snippet']['title'];
}

function getLien($listSearchResp){
    return "https://www.youtube.com/watch?v=". $listSearchResp['id']['videoId'];
}

function getDescription($listVideos){
    foreach ($listVideos['items'] as $detail){
        if (!empty($detail['snippet']['description'])){
            return str_replace("\n",' ',remove_emoji($detail['snippet']['description']));
        }
        else{
            return "";
        }
    }
}

function getTags($listVideos){
    foreach ($listVideos['items'] as $detail){
        if (!empty($detail['snippet']['tags'])){
            return implode(" ",str_replace(" ","_",$detail['snippet']['tags']));
        }else{
            return "";
        }
    }
}

function getDateUpload($listSearchResp){
    return date('Y-m-d', strtotime($listSearchResp['snippet']['publishedAt']));
}

function getNameChannel($channelID,$youtube){
    $response = $youtube->channels->listChannels('snippet', ['id'=>$channelID]);
    foreach ($response["items"] as $namechannel){
        return $namechannel['snippet']['title'];
    }
}

function getNbLike($listVideosResp){
    foreach ($listVideosResp['items'] as $detail){
        return $detail['statistics']['likeCount'];
    }
}

function getNbViews($listVideosResp){
    foreach ($listVideosResp['items'] as $detail){
        return $detail['statistics']['viewCount'];
    }
}


function getComm($idVideo,$nbdata,$ordre,$youtube){

    include 'Utils/data.php';
    $toCSV = array(
        $comCSV
    );//set le nom des colonnes
    $token = "";
    $i=0;

    do{

        $queryParams = [
            'videoId' => $idVideo,
            'textFormat' => 'plainText',
            'maxResults' => 100,
            'order'=>$ordre
        ];

        if(!empty($token)){//s'il y'a un token pour la page suivante
            $arr2 = array('pageToken' => $token);
            $queryParams = $queryParams + $arr2;//le met dans le tableau de parametre
        }

        $videoCommentThreads = $youtube->commentThreads->listCommentThreads('snippet,replies', $queryParams);//recupere tout les commantaires de la page

        $token = $videoCommentThreads->getNextPageToken();//genere le token pour la page suivante

        foreach($videoCommentThreads["items"] as $comm){
            $tmp = array();
            array_push($tmp,$comm['snippet']['topLevelComment']['id']);//id
            array_push($tmp,$comm['snippet']['totalReplyCount']);//Nombre de reponse
            array_push($tmp,$comm['snippet']['topLevelComment']['snippet']['likeCount']);//NbLike
            array_push($tmp,date('Y-m-d', strtotime($comm['snippet']['topLevelComment']['snippet']['publishedAt'])));//Date
            array_push($tmp,$comm['snippet']['topLevelComment']['snippet']['authorDisplayName']);//Auteur
            array_push($tmp,$comm['snippet']['topLevelComment']['snippet']['textOriginal']);//Texte
            array_push($toCSV, $tmp);

           if(!empty($comm['replies']['comments'])){//s'il y'a des réponses au commentaire courant
                for ($j = 0; $j<count($comm['replies']['comments']);$j++){
                    $tmpReplies = array();
                    array_push($tmpReplies,$comm['replies']['comments'][$j]['id']);//id
                    array_push($tmpReplies,"");//nbrep = null (on ne peut pas repondre à une reponse)
                    array_push($tmpReplies,$comm['replies']['comments'][$j]['snippet']['likeCount']);//NbLike
                    array_push($tmpReplies,date('Y-m-d', strtotime($comm['replies']['comments'][$j]['snippet']['publishedAt'])));//date
                    array_push($tmpReplies,$comm['replies']['comments'][$j]['snippet']['authorDisplayName']);//Auteur
                    array_push($tmpReplies,$comm['replies']['comments'][$j]['snippet']['textOriginal']);//Texte
                    array_push($tmpReplies,$comm['snippet']['topLevelComment']['id']);//repond a quel commentaire

                    array_push($toCSV, $tmpReplies);
                }
            }

        }
        $i++;
    }while($i<$nbdata/100);//boucle tant qu'il n'a pas atteint le nombre souhaité par l'utilisateur

    return $toCSV;
}