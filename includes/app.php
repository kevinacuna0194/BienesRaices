<?php

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';

/** Conectarse a la BD 
 * Crear una nueva instancia, nos retorna la instancia de la conexión a la base de datos.
 */
$db = conectarDB();

use App\ActiveRecord;

/** Importar Clase */

ActiveRecord::setDB($db);
 