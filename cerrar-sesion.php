<?php

/** Iniciar la sesión para poder acceder a ella */
session_start();
var_dump($_SESSION);
/* http://localhost:3000/cerrar-sesion.php
array(2) { ["usuario"]=> string(17) "correo@correo.com" ["login"]=> bool(true) } */

// session_unset();
// session_destroy();

/** Reescribir y asignar un arreglo vacio */
$_SESSION = [];

/* array(0) { } */

header('location: /');