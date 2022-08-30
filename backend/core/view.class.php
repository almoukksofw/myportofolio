<?php

/**
 * @author Jeroen van den Brink
 * @copyright 2020
 */

namespace core;

class View
{
    /** 
     * De weer te geven template
     * 
     * uitgangspunten:
     * - templatemap is ../templates (gezien vanuit public/index.php)
     * - extensie van template-bestanden is .template.php 
     */
    private $template;
    
    /** 
     * De associatieve array met variabelen, bedoeld voor de template.
     * De array wordt uitgepakt in de render-method, zodat daar locale variabelen ontstaan 
     * met de juiste namen (keys van $vars) en waarden (values van $vars). 
     */
    private $vars;
    
    /**
     * De constructor
     * - initialiseer de associatieve array met variabelen. 
     */
    public function __construct()
    {
        $this->vars = [];
        $this->add('_webroot', Router::getInstance()->getWebroot());
    }
    
    /** 
     * Instellen van de template
     * - voeg pad en extensie toe 
     */
    public function setTemplate($value)
    {
        $this->template = '../templates/' . $value . '.template.php';
    }
    
    /** 
     * Variabele toevoegen
     * - voeg een nieuw key-value-paar toe aan de associatieve array met variabelen. 
     */
    public function add($key, $value)
    {
        $this->vars[$key] = $value;
    }
    
    /** 
     * Het weergeven van de response
     * - pak de associatieve array met variabelen uit (levert locale variabelen)
     * - stop de template in de response
     */
    public function render()
    {
        extract($this->vars);
        require $this->template;
    }
}
