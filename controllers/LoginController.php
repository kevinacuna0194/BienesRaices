<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController
{
    public static function login(Router $router)
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Admin($_POST['admin']);

            $errores = $auth->validar();

            if (empty($errores)) {
                /** Verificar si el usuario existe */
                $resultado = $auth->existeUsuario();

                if (!$resultado) {
                    /** Verificar si el usuario existe o no (Mensaje de error) */
                    $errores = Admin::getErrores();
                } else {
                    /** Verificar el Password*/
                    $autenticado = $auth->comprobarPassword($resultado);

                    if ($autenticado) {
                        /** Autenticar el Usuario */
                        $auth->autenticar();
                    } else {
                        /** Password incorrecto (mensaje de error) */
                        $errores = Admin::getErrores();
                    }
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]);
    }

    public static function logout()
    {
        echo "Desde Logout";
    }
}
