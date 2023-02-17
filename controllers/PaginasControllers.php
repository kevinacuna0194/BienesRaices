<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;
use Model\Contacto;

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

    public static function nosotros(Router $router)
    {
        $router->render('paginas/nosotros');
    }

    public static function propiedades(Router $router)
    {
        $propiedades = Propiedad::all();

        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }

    public static function propiedad(Router $router)
    {
        $id = validarORedireccionar('/');

        $propiedad = Propiedad::find($id);

        $router->render('paginas/propiedad', [
            'propiedad' => $propiedad
        ]);
    }

    public static function blog(Router $router)
    {
        $router->render('paginas/blog');
    }

    public static function entrada(Router $router)
    {
        $router->render('paginas/entrada');
    }

    public static function contacto(Router $router)
    {
        $contacto = new Contacto();
        // Arreglo con mensajes de errores
        $errores = Contacto::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $contacto = new Contacto($_POST['contacto']);

            // Validar
            $errores = $contacto->validar();

            if (empty($errores)) {
                $mensaje = null;

                $respuestas = $_POST['contacto'];

                /** Crar instancia de PHPMailer */
                $phpmailer = new PHPMailer();

                /** Configurar SMTP (protocolo para envio de emails) */
                $phpmailer->isSMTP();
                $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Username = '5b9ec453cd6e12';
                $phpmailer->Password = '169fc04abc29c3';
                $phpmailer->SMTPSecure = 'tls'; // Encriptación
                $phpmailer->Port = 2525;

                /** Configurar el contenido del mail */
                $phpmailer->setFrom('admin@bienesraices.com');
                $phpmailer->addAddress('admin@bienesraices.com', 'BienesRaices.com');
                $phpmailer->Subject = 'Tienes un Nuevo Mensaje';

                /** Habilitar HTML */
                $phpmailer->isHTML(true);
                $phpmailer->CharSet = 'UTF-8';

                /** Definir el contenido */
                $contenido = '<html>';
                $contenido .= '<p><strong>Tienes un nuevo mensaje:</strong></p>';
                $contenido .= '<p>Nombre: ' . $respuestas['nombre'] . ' </p>';

                /** Enviar de forma condicional, algunos campos de email o teléfono */
                if ($respuestas['contacto'] === 'telefono') {
                    $contenido .= '<p><strong>Eligió ser contactado por Teléfono:</strong></p>';
                    $contenido .= '<p>Teléfono: ' . $respuestas['telefono'] . '</p>';
                    $contenido .= '<p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                    $contenido .= '<p>Hora: ' . $respuestas['hora'] . '</p>';
                } else {
                    /** Eligio Email */
                    $contenido .= '<p><strong>Eligió ser contactado por Email:</strong></p>';
                    $contenido .= '<p>Email: ' . $respuestas['email'] . '</p>';
                }

                $contenido .= '<p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
                $contenido .= '<p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
                $contenido .= '<p>Precio o Presupuesto: $ ' . $respuestas['precio'] . '</p>';
                $contenido .= '</html>';

                $phpmailer->Body = $contenido;
                $phpmailer->AltBody = 'Texto alternativo sin HTML';

                if ($phpmailer->send()) {
                    $mensaje = "Mensaje enviado Correctamente";
                } else {
                    $mensaje = "El mensaje no se puedo enviar, Mailer Error: {$phpmailer->ErrorInfo}";
                }
            }
        }

        $router->render('paginas/contacto', [
            'mensaje' => $mensaje,
            'errores' => $errores,
            'contacto' => $contacto
        ]);
    }
}
