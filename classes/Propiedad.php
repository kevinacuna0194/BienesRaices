<?php

namespace App;

class Propiedad
{
    /** Base de Datos 
     * Está de forma estática y protegida. No va a formar parte del constructor, no se va a reescribir nunca. Tenemos ya la referencia a la base de datos.
     */
    protected static $db;

    protected static $columnasDB = ['id', 'titulo', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

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

        $this->id = $args['id'] ?? '';
        $this->titulo = $args['titulo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->imagen = $args['imagen'] ?? 'imagen.jpg';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->wc = $args['wc'] ?? '';
        $this->estacionamiento = $args['estacionamiento'] ?? '';
        $this->creado = date('Y/m/d');
        $this->vendedorId = $args['vendedorId'] ?? '';
    }

    /** Definir la conexón a la BD 
     * El método también tiene que ser estático.
     */
    static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        /** Arreglo de Atributos ya Sanitizados */
        $atributos = $this->sanitizarAtributos();

        debuguear($atributos);

        /** Insertan en la BD */
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ('$this->titulo', '$this->precio', '$this->imagen', '$this->descripcion', '$this->habitaciones', '$this->wc', '$this->estacionamiento', '$this->creado', '$this->vendedorId')";

        $resultado = self::$db->query($query);

        debuguear($resultado);
    }

    /** Itera sobre $columnasDB 
     * Identificar y unir los atributos de la BD
     */
    public function atributos(): array
    {
        $atributos = [];
        foreach (self::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }

    public function sanitizarAtributos(): array
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        /** Arreglo asociativo. Mantener llave y valor. */
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }
}
