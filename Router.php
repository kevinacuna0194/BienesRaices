<?php

namespace MVC;

class Router
{
    public $rutasGET = [];
    public $rutasPOST = [];

    public function get($url, $fn)
    {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas()
    {
        session_start();

        /*
        array(2) {
        ["usuario"]=>
        string(17) "correo@correo.com"
        ["login"]=>
        bool(true)
        }
        */

        $auth = $_SESSION['login'] ?? null;

        /** Arreglo de rutas protegidas */
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual];
        } else {
            $fn = $this->rutasPOST[$urlActual];
        }

        if (in_array($urlActual, $rutas_protegidas) && !$auth) {
            header('location: /');
        }

        /** La URL existe y hay una función asociada */
        if ($fn) {
            call_user_func($fn, $this);
        } else {
            echo "Página no registrada";
        }
    }

    /** Muestra una vista */
    public function render($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        /** Guardar en memoria a que le estamos dando render y eso va a almacenar en la variable de $contenido.*/
        ob_start();
        include __DIR__ . '/views/' . $view . '.php';

        /** Limpiar memoria */
        $contenido = ob_get_clean();

        /** esta variable que está como $contenido se va a inyectar automáticamente justo en esta parte de nuestro layout.php (Master page) */
        include __DIR__ . '/views/layout.php';
    }
}
