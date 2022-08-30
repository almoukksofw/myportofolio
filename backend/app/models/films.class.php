<?php

namespace app\models;
use core\Model;


class Films extends Model{

    const TABLENAME='projects';

    public $persons;

    public function getRegisseurId(){
        return $this->getDataField('id_regisseur');
    }

    public function setId($value)
    {
        $this->setDataField('id', $value);
    }

    public function getId(){
        return $this->getDataField('id');
    }


    public function getRegisseur(){
        if(!isset($this->Regisseur)){
            $this->Regisseur=Regisseur::getFromDB($this->getRegisseurId());
        }
        return $this->Regisseur;
    }
    
    
    public function getActeurs(){
        if(!isset($this->Acteurs)){
            $this->Acteurs=Acteurs::indexByFilm($this->getId()) ;
        }
        return $this->Acteurs;
    }

    





  





}