<?php

/**
 * Class Autoloader
 */
class Autoloader{

    /**
     * Enregistre cet autoloader
     */
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Inclue le fichier correspondant à la classe
     * @param $class string Le nom de la classe à charger
     */
    static function autoload($class){
        if(file_exists('../src/Date/' . $class . '.php')){
        require_once '../src/Date/' . $class . '.php';}
        else {
        require_once '../src/App/' . $class . '.php';
        }
    }

}