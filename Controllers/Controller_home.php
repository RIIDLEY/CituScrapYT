<?php
session_start();
class Controller_home extends Controller{

    public function action_home(){
        $m = Model::getModel();
        include 'Utils/credentials.php';

        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);

        $client->setRedirectUri('http://localhost/Stage');
        $client->setScopes('https://www.googleapis.com/auth/youtube');

        $this->render('home',['client'=>$client]);
    }

    public function action_default(){
        $this->action_home();
    }

    public function action_deco(){//fonction qui deconnecte un administrateur

        if (session_unset()){//detruit les variables sessions
            echo("<script>window.location = 'index.php';</script>");//retourne vers la page principale
        }

    }
}

?>
