<?php

namespace Controllers;
use MVC\Router;
use Model\Vendedor;

class VendedorController {

    public static function crear(Router $router) {

        $errores = Vendedor::getErrores();

        $vendedor = new Vendedor;

        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }

    public static function actualizar() {
        echo 'Actualizar Vendedor';
    }

    public static function eliminar() {
        echo 'Eliminar Vendedor';
    }
}