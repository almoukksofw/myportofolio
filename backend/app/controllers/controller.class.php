<?php

namespace app\controllers;

use \core\View;
use \core\Json;
use \core\Session;
use \core\Token;



abstract class Controller{
 
    protected $view;    
    protected $session;
    protected $token;


    public function __construct(){
        $this->session = Session::getInstance();
        
        $this->json = new Json();

        $this->token = new Token();        
        // $this->token->authenticate();
        
        $this->view = new View();
        $this->view->add('_message', $this->session->getOnce('message'));
        
        // if ($this->token->isValid()) {
        //     $this->view->add('_authuser', $this->token->getUser());
        // }
    }

    /**
     * method voor interne (= binnen de applicatie) redirects
     */
    protected function redirect($url){
        $webroot = \core\Router::getInstance()->getWebroot();        
        
        header('location: ' . $webroot . $url);
        die();
    }

    
}