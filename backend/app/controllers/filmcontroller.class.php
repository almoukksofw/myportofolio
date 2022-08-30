<?php

namespace app\controllers;
use app\controllers\Controller;
use app\models\Films;
use core\Router;

require 'controller.class.php';

class filmController extends Controller{

    public function films_index(){ // returns only jso
        $data=[];
        $films=Films::index();

        foreach($films as $film){
            $data[]=$film->getData();
        }
        $this->json->add( 'films', $data);
        $this->json->render();
    }


    public function FilmShow(){ //geein

        $film = new Films();
        $UriString=explode('/' , $_SERVER['REQUEST_URI']); 
        $id=$UriString[sizeof($UriString)-1 ] ; 
        
        $film->setId($id);
        $film->load($success);
        if (!$success){
            // $this->view->setTemplate('404');
        }else{
            $filmData=$film->getData();
            $filmData["acteurs"]=$film->getActeurs();
            $filmData["regisseur"]=$film->getRegisseur();
            $this->json->add('film', $filmData  );   
        }
        $this->json->render();
    }





}