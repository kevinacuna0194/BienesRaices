<?php

/** Importar la conexión */
require 'includes/app.php';
$db = conectarDB();

/** Crear un email y password */
$email = "correo@correo.com";
$password = "123456";

/** Toma dos argumentos 1- Password que vamos a hashear. 2- Algoritmo que se va a utilizar para hashear este password. **/
// $passwordHash = password_hash($password, PASSWORD_DEFAULT);
$passwordHash = password_hash($password, PASSWORD_BCRYPT); // string(60) "$2y$10$DD/.iVjTyYkSl7m5zHTWPOmPPrOiCH/upSCI2Ajrski8T49ldnI0e"

/** Query para crear un usuario */
$query = "INSERT INTO usuarios (email, password) VALUES ('{$email}', '{$passwordHash}')";

/** Agregarlo a la BD */
mysqli_query($db, $query);