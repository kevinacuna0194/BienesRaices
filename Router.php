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
        include __DIR__ . '/views/' . $view . '.php';
    }
}
