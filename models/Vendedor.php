<?php

namespace Model;

class Vendedor extends ActiveRecord
{
    protected static $tabla = 'vendedores';

    protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono'];

    public $id;
    public $nombre;
    public $apellido;
    public $telefono;

    public function __construct($args = [])
    {

        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
    }

    public function validar()
    {
        if (!$this->nombre) {
            self::$errores[] = 'El Nombre es obligatorio';
        }

        if (!$this->apellido) {
            self::$errores[] = 'El Apellido es obligatorio';
        }

        if (!$this->telefono) {
            self::$errores[] = 'El Teléfono es obligatorio';
        }

        /** Perform a regular expression match 
         *  Una expresión regular es una forma de buscar un patrón dentro de un texto.
         * / / Tamaño fijo
         * [0-9] los valores que va a aceptar van desde cero a 9, es decir, no podemos ponerle letras.
         * {10} 10 dígitos.
         * Básicamente le decimos que es una extensión fija de diez dígitos y solamente acepta números del 09.
        */
        if (!preg_match('/[0-9]{10}/', $this->telefono)) {
            self::$errores[] = 'Formato no válido';
        }

        return self::$errores;
    }
}
