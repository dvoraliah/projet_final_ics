<!-- Ce fichier contient toutes les fonctions utiles sur l'ensemble de mon site, il est appelé dans la quasi totalité de mes pages afin de centraliser mes fonctions couramment utilisées -->

<?php
require_once '../public/autoloader.php';
Autoloader::register();
/**
 * Affiche ma page d'erreur et stop mon code
 *
 * @return void
 */
function e404(){
    require '../public/404.php';
    exit();
}
/**
 * Fonction permettant d'afficher dynamiquement mon header avec un tableau de paramètres variables
 *
 * @param string $view
 * @param array $param
 * @return void
 */
function render(string $view, $param = []){
    extract($param);
    include "../views/{$view}.php";
}

/**
 * Fonction qui me permet de debugger mon code
 *
 * @param [type] $vars
 * @return void
 */
function dd(...$vars) {

    foreach ($vars as $var) {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

/**
 * Fonction permettant d'acceder à la base de donnée
 *
 * @return PDO
 */
function get_pdo (): PDO {
    return $pdo = new \PDO('mysql:host=localhost;dbname=calendar_paradiscanin', 'root', 'root', [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ]);
}
/**
 * Fonction permettant d'empecher les injections de code dans ma base de données
 *
 * @param string $value
 * @return string
 */
function h(string $value):string {
    if($value === null){
        return '';
    }
    return htmlentities($value);
}