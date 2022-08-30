<?php

function addClass($class){

    $fileName= str_replace('\\','/',$class);
    $classFile='../'. strtolower($fileName).'.class.php';
    // str_replace("\/",'/',$classFile);

    require_once $classFile;
}


spl_autoload_register('addClass');



?>