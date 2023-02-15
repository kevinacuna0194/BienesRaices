<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;

class PaginasControllers
{
    public static function index(Router $router)
    {   
        $propiedades = Propiedad::get(3);
        $inicio = true;

        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }

    public static function nosotros()
    {
        echo "desde el nosotros...";
    }

    public static function propiedades()
    {
        echo "desde el propiedades...";
    }

    public static function propiedad()
    {
        echo "desde el propiedad...";
    }

    public static function blog()
    {
        echo "desde el blog...";
    }

    public static function entrada()
    {
        echo "desde el entrada...";
    }

    public static function contacto()
    {
        echo "desde el contacto...";
    }
}
