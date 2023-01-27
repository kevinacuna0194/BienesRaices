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

    if (empty($errores)) {
        $query = "SELECT * FROM usuarios WHERE email = '{$email}'";

        $resultado = mysqli_query($db, $query);

        /*
        echo '<pre>';
        var_dump($resultado);
        echo '</pre>';
        /*
        object(mysqli_result)#2 (5) {
        ["current_field"]=>
        int(0)
        ["field_count"]=>
        int(3)
        ["lengths"]=>
        NULL
        ["num_rows"]=>
        int(1)
        ["type"]=>
        int(0)
        }
        */

        /** Comprobar que haya resultados en la consulta (si existe) */
        if ($resultado->num_rows) {
            /** Revisar si el password es correcto */
            $usuario = mysqli_fetch_assoc($resultado);

            /** @param string $password — The user's password. 
             * @param string $hash — A hash created by password_hash(). 
             * @return bool — Returns TRUE if the password and hash match, or FALSE otherwise. */
            $auth = password_verify($password, $usuario['password']);

            if ($auth) {
                /** El usuario está autenticado */
                session_start();

                /*
                echo '<pre>';
                var_dump($_SESSION);
                echo '</pre>';
                /*
                array(0) { }
                */

                /** Llenar el arreglo de la sesión */
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

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

                header('location: /admin');
            } else {
                /** Password incorrecto */
                $errores[]  = 'El Password es incorrecto';
            }
        } else {
            $errores[] = 'El Usuario no existe';
        }
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