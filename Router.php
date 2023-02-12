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

    public function comprobarRutas()
    {
        $urlActual = $_SERVER['PATH_INFO'] ?? '/';
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual];
        }

        /** La URL existe y hay una función asociada */
        if ($fn) {
            call_user_func($fn, $this);
        } else {
            echo "Página no registrada";
        }
    }

    /** Muestra una vista */
    public function render($view) {

        /** Guardar en memoria a que le estamos dando render y eso va a almacenar en la variable de $contenido.*/
        ob_start();
        include __DIR__ . '/views/' . $view . '.php';

        /** Limpiar memoria */
        $contenido = ob_get_clean();

        /** esta variable que está como $contenido se va a inyectar automáticamente justo en esta parte de nuestro layout.php (Master page) */
        include __DIR__ . '/views/layout.php';
    }
}
