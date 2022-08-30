<?php


namespace core;
use PDO;



class Database{
    private static $instance;
    
    
    private $pdo;

    private function __construct(){
		$dsn = 'mysql:host=localhost; dbname=portofolio';
		$this->pdo = new PDO($dsn, 'noorderpoort', 'toets');
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);        
    }



    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getPdo(){
        return $this->pdo; 
    }

}

?>