<?php

/**
 * @author Jeroen van den Brink
 * @copyright 2020
 */

namespace core;

class Session
{
    /**
     * static object van de class Session
     */
    private static $instance;
    
    
    /**
     * private constructor blokkeert het gebruik van new om Session-objecten te maken
     */
    private function __construct()
    {
        session_start();
    }
    
    /**
     * private clone-method
     */
    private function __clone()
    {
        // gebruiken we niet
    }
    
    /**
     * getter voor het singleton object van de class Session
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }    
 
    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }
    
    public function getOnce($key)
    {
        $value = $this->get($key);
        if (isset($value))
        {
            $this->remove($key);
        }
        return $value;
    }
    
    
}