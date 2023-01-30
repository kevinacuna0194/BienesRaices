<?php

/* Constantes - Llave y valor */
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETA_IMAGENES', __DIR__ . '/../imagenes/');

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
    exit;
}

function estaAutenticado()
{
    session_start();

    if (!$_SESSION['login']) {
        header('location: /');
    }
}

