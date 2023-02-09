<?php

namespace App;

class ActiveRecord
{
    /** Base de Datos 
     * Está de forma estática y protegida. No va a formar parte del constructor, no se va a reescribir nunca. Tenemos ya la referencia a la base de datos.
     */
    protected static $db;

    protected static $columnasDB = ['id', 'titulo', 'precio', 'imagen', 'descripcion', 'habitaciones', 'wc', 'estacionamiento', 'creado', 'vendedorId'];

    protected static $tabla = '';

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

    /** Definir la conexón a la BD 
     * El método también tiene que ser estático.
     */
    static function setDB($database)
    {
        self::$db = $database;
    }

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
        $this->vendedorId = $args['vendedorId'] ?? 1;
    }

    public function guardar()
    {
        if (!is_null($this->id)) {
            /** Actualizar */
            $this->actualizar();
        } else {
            /** Crear un nuevo registro */
            $this->crear();
        }
    }

    public function crear()
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
        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        // debuguear($query);
        /** string(609) "INSERT INTO propiedades (titulo, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ('Neverland', 'imagen.jpg', '\r\nVivamus at lectus sit amet nunc viverra viverra. Nunc libero magna, bibendum vitae erat nec, venenatis pharetra felis. Proin nunc diam, consectetur at tellus id, porta suscipit lacus. Aenean eu velit libero. Etiam at pharetra lacus. Praesent a lectus sit amet orci consectetur fermentum. Pellentesque commodo auctor ultrices. Maecenas urna purus, ullamcorper vel turpis tempor, lobortis feugiat nisl. Pellentesque porttitor.', '1', '1', '1', '2023/01/29', '2')" */

        $resultado = self::$db->query($query);

        /** Mensaje de éxito o error */
        if ($resultado) {
            /** Redireccionar al usuario */
            header('location: /admin?resultado=1');
        }
    }

    public function actualizar()
    {
        /** Arreglo de Atributos ya Sanitizados */
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "$key = '$value'";
        }

        /*
        debuguear($valores);
        /*
        array(9) {
        [0]=>
        string(31) "titulo = 'The Witanhurst House'"
        [1]=>
        string(20) "precio = '150000.00'"
        [2]=>
        string(47) "imagen = '63e632e839c7f2cca3c9e68b1a606597.jpg'"
        [3]=>
        string(378) "descripcion = 'In suscipit vestibulum mauris, eget venenatis urna. Sed ullamcorper faucibus felis et pharetra. Vestibulum condimentum tortor ac sodales ullamcorper. Nunc neque dolor, luctus non nibh ac, pretium ultrices leo. Curabitur sed eros augue. Suspendisse non eros ligula. Nam vitae semper enim. Vivamus pulvinar molestie tristique. Integer nec nulla hendrerit, rhoncus.'"
        [4]=>
        string(18) "habitaciones = '2'"
        [5]=>
        string(8) "wc = '2'"
        [6]=>
        string(21) "estacionamiento = '2'"
        [7]=>
        string(21) "creado = '2023-01-26'"
        [8]=>
        string(16) "vendedorId = '1'"
        }
        */

        /*
        debuguear(join(', ', $valores));
        /*
        string(576) "titulo = 'The Witanhurst House', precio = '150000.00', imagen = '63e632e839c7f2cca3c9e68b1a606597.jpg', descripcion = 'In suscipit vestibulum mauris, eget venenatis urna. Sed ullamcorper faucibus felis et pharetra. Vestibulum condimentum tortor ac sodales ullamcorper. Nunc neque dolor, luctus non nibh ac, pretium ultrices leo. Curabitur sed eros augue. Suspendisse non eros ligula. Nam vitae semper enim. Vivamus pulvinar molestie tristique. Integer nec nulla hendrerit, rhoncus.', habitaciones = '2', wc = '2', estacionamiento = '2', creado = '2023-01-26', vendedorId = '1'"
        */

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $query .= " LIMIT 1";

        /*
        debuguear($query);
        /*
        string(622) "UPDATE propiedades SET titulo = 'The Witanhurst House', precio = '150000.00', imagen = '63e632e839c7f2cca3c9e68b1a606597.jpg', descripcion = 'In suscipit vestibulum mauris, eget venenatis urna. Sed ullamcorper faucibus felis et pharetra. Vestibulum condimentum tortor ac sodales ullamcorper. Nunc neque dolor, luctus non nibh ac, pretium ultrices leo. Curabitur sed eros augue. Suspendisse non eros ligula. Nam vitae semper enim. Vivamus pulvinar molestie tristique. Integer nec nulla hendrerit, rhoncus.', habitaciones = '2', wc = '2', estacionamiento = '2', creado = '2023-01-26', vendedorId = '1' WHERE id = '5' LIMIT 1"
        */

        $resultado = self::$db->query($query);

        if ($resultado) {
            /** Redireccionar al usuario */
            header('location: /admin?resultado=2');
        }
    }

    /** Eliminar un registro */
    public function eliminar()
    {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";

        /*
        debuguear($query);
        /** string(45) "DELETE FROM propiedades WHERE id = 34 LIMIT 1" */

        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();

            header('location: /admin?resultado=3');
        }
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

    /** Subida de archivos */
    public function setImagen($imagen)
    {
        /** Elimina la imagen previa 
         * if ($this->id) significa que estoy editando.
         */
        if (!is_null($this->id)) {
            $this->borrarImagen();
        }

        //asignar nombre de la imagen
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    /** Eliminar archivo */
    public function borrarImagen()
    {
        // debuguear($this);

        /** Comprobar si existe el archivo */
        $exiteArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($exiteArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
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

        if (!$this->imagen) {
            self::$errores[] = 'La Imagen es obligatoria';
        }

        return self::$errores;
    }

    /** Listar toas las propiedades */
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    /** Buscar una propiedad por su ID */
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id}";

        $resultado = self::consultarSQL($query);

        return array_shift($resultado); // array_shift - Retorna la primer posición de un arreglo.
    }

    public static function consultarSQL($query)
    {
        /** 1- Consulatar la BD*/
        $resultado = self::$db->query($query);

        /** 2- Iterar los resultados*/
        /* debuguear($resultado->fetch_assoc()); 1 Resultado */
        $array = [];

        while ($registro = $resultado->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        /* debuguear($array); Un arreglo que contiene 1 objeto por cada resultado de la consulta a la BD */

        /** 3- Liberar la memoria porque terminamos de hacer la consulta */
        $resultado->free();

        /** 4- Retornar los resultados */
        return $array;
    }

    /** Básicamente va a tomar estos arreglos de la base de datos, porque así vienen como arreglos y nos va a crear unos objetos. */
    protected static function crearObjeto($registro)
    {
        $objeto = new self; // Clase Padre. Nueva instancia a Propiedad dentro de nuestra clase.

        /*
        debuguear($objeto);
        /*
        object(App\Propiedad)#5 (10) {
        }
        */

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) { // Coompara la LLave del nuevo objeto en memoria, con la llave del arreglo asociatico con el resultado de la consulta a la BD.
                $objeto->$key = $value; // Asigna a la llave del objeto el valor del arreglo.
            }
        }

        return $objeto;
    }

    /** Sincroniza el objeto en memoria con los cambios relizados por el Usuario */
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
