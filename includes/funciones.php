<?php

require 'app.php';

function incluirTemplate(string $nombre, bool $inicio = false)
{   
    /* echo TEMPLATES_URL . "/${nombre}.php"; C:\Apache\htdocs\bienesraices\includes/templates/header.php */
    include TEMPLATES_URL . "/${nombre}.php";
}

function debuguear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}
