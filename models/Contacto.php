<?php

namespace Model;

class Contacto extends ActiveRecord
{
    public $nombre;
    public $mensaje;
    public $tipo;
    public $precio;
    public $contacto;
    public $telefono;
    public $fecha;
    public $hora;
    public $email;

    public function __construct($args = [])
    {
        $this->nombre = $args['nombre'] ?? '';
        $this->mensaje = $args['mensaje'] ?? '';
        $this->tipo = $args['tipo'] ?? '';
        $this->precio = $args['precio'] ?? '';
        $this->contacto = $args['contacto'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->email = $args['email'] ?? '';
    }

    public function validar()
    {
        if (!$this->nombre) {
            self::$errores[] = 'El Nombre es obligatorio';
        }

        if (!$this->mensaje) {
            self::$errores[] = 'El Mensaje es obligatorio';
        }

        if (!$this->tipo) {
            self::$errores[] = 'Es obligatorio infomar si Vende o Compra';
        }

        if (!$this->precio) {
            self::$errores[] = 'El Precio es obligatorio';
        }

        if (!$this->contacto) {
            self::$errores[] = 'Debe informar como desea ser contactado';
        }

        if ($this->contacto === 'telefono') {
            if (!$this->telefono) {
                self::$errores[] = 'el Teléfono es obligatorio';
            }

            if (!$this->fecha) {
                self::$errores[] = 'La Fecha es obligatorio';
            }

            if (!$this->hora) {
                self::$errores[] = 'La Hora es obligatoria';
            }
        }

        if ($this->contacto === 'email') {
            if (!$this->email) {
                self::$errores[] = 'El email es obligatorio';
            }
        }

        return self::$errores;
    }
}
