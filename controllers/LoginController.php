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
                    $errores = Admin::getErrores();
                } else {
                    /** Verificar el Password*/

                    /** Autenticar el Usuario */
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
