<?php

namespace core;


class Json{
    private $data;

    public function __construct(){
        $this->setStatus(200, 'ok');
    }



    public function setStatus($code, $message){
        $this->add('status', ['code' => $code, 'message' => $message]);
    }

    public function add($key, $value){
        $this->data[$key] = $value;
    }


    public function render(){
        header('Content-Type: application/json; charset=utf8');
        header('Access-Control-Allow-Origin');
        header('Access-Control-Allow-Origin: http://localhost:8080');
        // header('Access-Control-Allow-Credentials: true');
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
        header('Access-Control-Max-Age: 1000');
        echo json_encode($this->data);
        
    }
}