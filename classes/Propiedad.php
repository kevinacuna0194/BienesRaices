<?php

namespace App;

class Propiedad
{
    /** Base de Datos 
     * Está de forma estática y protegida. No va a formar parte del constructor, no se va a reescribir nunca. Tenemos ya la referencia a la base de datos.
     */
    protected static $db;

    protected static $columnasDB = ['id', 'titulo', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    /** Errores */
    protected static $errores = [];

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

        /** Conbertir las posiciones del array_keys() en strings 
         * 2 parametros. 1- Separador. 2- Array
         */
        // $string = join(', ', array_keys($atributos));
        // $string = join(', ', array_values($atributos));
        // debuguear($string);
        /** string(82) "titulo, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId" */
        /** string(475) "Neverland, imagen.jpg, \r\nVivamus at lectus sit amet nunc viverra viverra. Nunc libero magna, bibendum vitae erat nec, venenatis pharetra felis. Proin nunc diam, consectetur at tellus id, porta suscipit lacus. Aenean eu velit libero. Etiam at pharetra lacus. Praesent a lectus sit amet orci consectetur fermentum. Pellentesque commodo auctor ultrices. Maecenas urna purus, ullamcorper vel turpis tempor, lobortis feugiat nisl. Pellentesque porttitor., 1, 1, 1, 2023/01/29, 2" */

        /** Insertan en la BD */
        $query = "INSERT INTO propiedades (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        // debuguear($query);
        /** string(609) "INSERT INTO propiedades (titulo, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ('Neverland', 'imagen.jpg', '\r\nVivamus at lectus sit amet nunc viverra viverra. Nunc libero magna, bibendum vitae erat nec, venenatis pharetra felis. Proin nunc diam, consectetur at tellus id, porta suscipit lacus. Aenean eu velit libero. Etiam at pharetra lacus. Praesent a lectus sit amet orci consectetur fermentum. Pellentesque commodo auctor ultrices. Maecenas urna purus, ullamcorper vel turpis tempor, lobortis feugiat nisl. Pellentesque porttitor.', '1', '1', '1', '2023/01/29', '2')" */

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

    /** Validación */
    public static function getErrores()
    {
        return self::$errores;
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

        /** Validar por tamaño (1 MB máximo ~ 1000 KB ~ 100.000.000 bytes) */
        $medida = 1000 * 1000;
        if ($imagen['size'] > $medida) {
            $errores[] = 'La imagen es muy pesada';
        }

        return self::$errores;
    }
}
