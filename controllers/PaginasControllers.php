<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            $contenido = '<html> <p> Tienes un nuevo mensaje </p> </html>';
            $phpmailer->Body = $contenido;
            $phpmailer->AltBody = 'Texto alternativo sin HTML';

            if ($phpmailer->send()) {
                echo "Mensaje enviado Correctamente";
            } else {
                echo "El mensaje no se puedo enviar, Mailer Error: {$phpmailer->ErrorInfo}";
            }
        }

        $router->render('paginas/contacto', []);
    }
}
