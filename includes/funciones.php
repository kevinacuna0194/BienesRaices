<?php

require 'app.php';

function incluirTemplate(string $nombre, bool $inicio = false)
{
    /* echo TEMPLATES_URL . "/${nombre}.php"; C:\Apache\htdocs\bienesraices\includes/templates/header.php */
    include TEMPLATES_URL . "/$nombre.php";
}

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}

function estaAutenticado(): bool
{
    session_start();

    /*
    echo '<pre>';
    var_dump($_SESSION);
    echo '</pre>';
    /*
    array(2) {
      ["usuario"]=>
      string(17) "correo@correo.com"
      ["login"]=>
      bool(true)
    }
    */

    /** Leer la variable de $_SESSION */
    $auth = $_SESSION['login'];

    if ($auth) {
        return true;
    }

    return false;
}
