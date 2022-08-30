<?php

/**
 * @author Jeroen van den Brink
 * @copyright 2020
 */

namespace core;

/**
 * Objecten van de class Route worden gedefinieerd in de configuratiefile routes.conf.php
 */
class Route
{
    /**
     * properties
     */
    private $request_url;           // geconfigureerde reguliere expressie
    private $request_method;        // geconfigureerde waarde (GET of POST)
    private $request_parameters;    // worden bepaald met behulp van de reguliere expressie 
    
    private $controller_class;      // geconfigureerde naam van de contoller-class
    private $controller_method;     // geconfigureerde naam van de controller-method

    /**
     * constructor
     * - leg de vier geconfigureerde properties vast
     */    
    public function __construct($request_url, $request_method, $controller_class, $controller_method)
    {
        $this->request_url          = $request_url; 
        $this->request_method       = $request_method;
        $this->controller_class     = $controller_class;
        $this->controller_method    = $controller_method;
    }
    /**
     * check of de route matcht met een request
     */
    public function matches(Request $request)
    {
        return $this->methodMatches($request->getMethod()) && $this->uriMatches($request->getUri());
    }
    
    private function methodMatches($method)
    {
        $ok = ($method == $this->request_method);
        return $ok;
    }
    
    private function uriMatches($uri)
    {
        // gebruik een teken als delimiter dat niet voorkomt in de geconfigureerde urls
        $ok = preg_match('#^' . $this->request_url . '$#', $uri, $matches);
        if ($ok) {
            $this->request_parameters = array_slice($matches, 1);
        }
        return $ok;
    }
    
 
    public function deploy()
    {
        $class = '\\app\\controllers\\' . $this->controller_class;
        
        $controller = new $class();
        
        $callable = [$controller, $this->controller_method];
        
        call_user_func_array($callable, $this->request_parameters);
          
    }
    
}
