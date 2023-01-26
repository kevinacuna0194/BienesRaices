<?php

/** Importar la conexión */
require 'includes/config/database.php';
$db = conectarDB();

/** Crear un email y password */
$email = "correo@correo.com";
$password = "123456";

/** Query para crear un usuario */
$query = "INSERT INTO usuarios (email, password) VALUES ('{$email}', '{$password}')";

/** Agregarlo a la BD */
mysqli_query($db, $query);