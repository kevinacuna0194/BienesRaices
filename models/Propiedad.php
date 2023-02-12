<?php

namespace Model;

class Propiedad extends ActiveRecord
{
    protected static $tabla = 'propiedades';

    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    public $id;
    public $titulo;
    public $precio;
    public $imagen;
    public $descripcion;
    public $habitaciones;
    public $wc;
    public $estacionamiento;
    public $creado;
    public $vendedorId;

    public function __construct($args = [])
    {

        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    public function validar()
    {
        if (!$this->titulo) {
            self::$errores[] = 'Debes añadir un Título';
        }

        if (!$this->precio) {
            self::$errores[] = 'El Precio es obligatorio';
        }

        if (strlen($this->descripcion) < 25) {
            self::$errores[] = 'La Descripcion es obligatoria y debe tener al menos 25 caracteres';
        }

        if (!$this->habitaciones) {
            self::$errores[] = 'El número de Habitaciones es obligatoria';
        }

        if (!$this->wc) {
            self::$errores[] = 'El número de Baños es obligatorio';
        }

        if (!$this->estacionamiento) {
            self::$errores[] = 'El número de lugares de Estacionamiento es obligatorio';
        }

        if (!$this->vendedorId) {
            self::$errores[] = 'Elige un Vendedor';
        }

        if (!$this->imagen) {
            self::$errores[] = 'La Imagen de la propiedad es obligatoria';
        }

        return self::$errores;
    }
}
