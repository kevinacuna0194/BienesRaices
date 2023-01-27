<?php
/** Conectar BD */
require 'includes/config/database.php';
$db = conectarDB();

$errores = [];

/** Autenticar el usuario */
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // debuguear($_POST);
    /*
    array(2) {
    ["email"]=>
    string(17) "correo@correo.com"
    ["password"]=>
    string(6) "123456"
    }
    */

    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)); // Retorna si es un email válido. validación del navegador.
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = 'El Email es obligatorio o no es válido';
    }

    if (!$password) {
        $errores[] = 'El Password es obligatorio';
    }

    if(empty($errores)) {
        
    }
}

require 'includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form method="POST" class="formulario"><!-- novalidate -->
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email"><!-- required -->

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu password" id="password"><!-- required -->
        </fieldset>

        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>