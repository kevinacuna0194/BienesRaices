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

/** Escapa el HTML */
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

/** Validar tipo de contenido */
function validarTipoContenido($tipo)
{
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos);
    /** buscar un string dentro de un arreglo o un valor dentro de un arreglo. Toma dos valores, lo que vamos a buscar y el segundo es el arreglo donde lo va a buscar. */
}

/** Mostrar Mensajes */
function mostrarNotificacion($codigo)
{
    $mensaje = '';

    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = '';
            break;
    }

    return $mensaje;
}
