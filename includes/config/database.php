<?php

function conectarDB() : mysqli
{
    /** (string|null $hostname = null, string|null $username = null, string|null $password = null, string|null $database = null, int|null $port = null, string|null $socket = null */
    $db = mysqli_connect('localhost', 'root', 'root', 'bienesraices_crud');

    if (!$db) {
        echo 'Error no se puedo conectar';
        exit;
    }

    return $db; // Retornar la instancia de la conexión.
}
