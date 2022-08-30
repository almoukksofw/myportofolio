<?php

namespace core;
use \core\Database;
use PDO;

abstract class Model {
    
    /** door alle child classes gedeelde properties */ 
    
    protected $pdo;         /** de databaseconnectie */
    
    /** private properties */

    private $data;          /** de associatieve array met record-gegevens van het object */
    private $primary_name;  /** de naam van het primary-key-veld */
    private $primary_type;  /** het pdo-param-type van het primary-key-veld */
    
    private $errors;        /** de associatieve array met validatie-errors */
    
    
    /** 
     * De constructor
     * - kan worden aangeroepen door de child constructor
     * - de parameter bevat de definitie van de primary key, nodig voor generieke database-methods
     *   default: primary key is een integer met de naam id
     * - initialiseer de pdo-property
     * - initialiseer de primary name (default id)
     * - initialiseer de primary type (default PARAM_INT)
     */
    public function __construct($primary_def = ['id', PDO::PARAM_INT])
    {
        $this->pdo = Database::getInstance()->getPdo();
        
        $this->primary_name = $primary_def[0];
        $this->primary_type = $primary_def[1];
    }
   
    /** getter voor alle data (heel record) in 1x */
    public function getData()
    {
        return $this->data;
    }

    /** getter voor data (specifiek veld) */ 
    protected function getDataField($name)
    {
        return $this->data[$name] ?? NULL;
    }


    /** getter voor de waarde van de primary key (generiek) */
    protected function getPrimaryValue(){
        return $this->getDataField($this->primary_name);
    }


    
    
    /** 
     * magic getter
     * 
     * De magic getter (en setter) komt in actie wanneer je een property probeert te benaderen
     * die niet bestaat, of die niet beschikbaar is (bijv. omdat hij private is).
     * 
     * Je mag het gebruiken; het scheelt een hoop getters (en setters), en het komt de
     * leesbaarheid van je request-scripts ten goede. Maar wees er voorzichtig mee.
     */
    public function __get($name)
    {
        return $this->getDataField($name);
    }
    
    /** setter voor alle data (heel record) in 1x */
    protected function setData($value)
    {
        foreach ($value as &$str)
        {
            $str = utf8_encode($str);
        }
        $this->data = $value;
    }
    
    /** setter voor data (specifiek veld) */
    protected function setDataField($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    /** 
     * validatie-errors
     * - een method om een error op te slaan
     * - een method om de errors op te halen
     * - eem method om te checken of er errors zijn 
     */
    protected function setError($name, $value)
    {
        $this->errors[$name] = $value;
    }
    
    public function getErrors()
    {
        return $this->errors ?? [];
    }
    
    public function isValid()
    {
        return count($this->getErrors()) == 0;
    }
    
    
    /** generieke database-methods */
    
    /** deleten van een database-record op grond van de primary key */
    public function delete(&$success)
    {
        $query =
        '
            DELETE 
            FROM ' . $this::TABLENAME . '
            WHERE ' . $this->primary_name . ' = :pk
        ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':pk', $this->getPrimaryValue(), $this->primary_type);
        $statement->execute();
        $success = ($statement->rowCount() == 1);
        /** misschien wel elegant om hier de data-property leeg te maken... */
    }
    
    /** ophalen van een database-record op grond van de primary key */
    public function load(&$success)
    {
        $query = 
        '
            SELECT *
            FROM ' . $this::TABLENAME . '
            WHERE ' . $this->primary_name . ' = :pk
        ';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':pk', $this->getPrimaryValue(), $this->primary_type);
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        $success = ($data != false);
        if ($success)
        {
            $this->setData($data);
        }
    }
    
    /** static */
    
    /** 
     * De index-method (ophalen van alle records) is generiek gemaakt.
     * 
     * Let op de volgende trucs:
     * - de classnaam ($class) wordt bepaald met de functie get_called_class()
     * - de classnaam is nodig om de juiste tabelnaam te vinden, die als constante is gedefinieerd in elke class
     * - en de classnaam is nodig om nieuwe objecten te kunnen maken: new $class()
     * 
     * Deze method retourneert een array met alle objecten van de aanvragende child class.
     */
    static public function index() 
    {
        $pdo = Database::getInstance()->getPdo();           /** database-connectie */
        
        $class = get_called_class();                        /** ��n van de child classes */

        $query =                                            /** haal alle records op */
        '
            SELECT *
            FROM ' . $class::TABLENAME . '
        ';
        
        $statement = $pdo->prepare($query);                 /** query uitvoeren */
        $statement->execute();

        $records = $statement->fetchAll(PDO::FETCH_ASSOC);  /** records ophalen als assoc. arrays */
        $objects = [];                                      /** records moeten model-objects worden */

        foreach ($records as $record)
        {
            $object = new $class();                         /** maak object van child class */
            $object->setData($record);                      /** stop data erin */
            $objects[] = $object;                           /** voeg toe aan return-array */
        }
        
        return $objects;
    }
    
}