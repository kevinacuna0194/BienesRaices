<?php

require '../../includes/app.php';

use App\Propiedad;
/* import the Intervention Image Manager Class */
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();

/** BD */
$db = conectarDB();

/** Instanciar clase */
$propiedad = new Propiedad;

/** Consultar para obtener los vendedores **/
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

/** Arreglo con mensajes de errores 
 * Para que no marque undefined **/
$errores = Propiedad::getErrores();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    /** Crear Objeto con la instancia a la Clase */
    $propiedad = new Propiedad($_POST['propiedad']);

    /*
    debuguear($_FILES);
    /*
        array(1) {
    ["propiedad"]=>
    array(6) {
        ["name"]=>
        array(1) {
        ["imagen"]=>
        string(12) "anuncio1.jpg"
        }
        ["full_path"]=>
        array(1) {
        ["imagen"]=>
        string(12) "anuncio1.jpg"
        }
        ["type"]=>
        array(1) {
        ["imagen"]=>
        string(10) "image/jpeg"
        }
        ["tmp_name"]=>
        array(1) {
        ["imagen"]=>
        string(45) "C:\Users\kevin\AppData\Local\Temp\phpE48C.tmp"
        }
        ["error"]=>
        array(1) {
        ["imagen"]=>
        int(0)
        }
        ["size"]=>
        array(1) {
        ["imagen"]=>
        int(94804)
        }
    }
    }
    */

    /** Subida de archivos **/
    /** 2- Generar un nombre único */
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        /** Setear la imagen */
        /** Realiza un resize a la imagen con Intervention */
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    /** Validar */
    $errores = $propiedad->validar();

    /** revisar que el Array de errores este vacio **/
    if (empty($errores)) {

        /** Crear carpeta para subir imagenes */
        if (!is_dir(CARPETA_IMAGENES)) {
            mkdir(CARPETA_IMAGENES);
        }
        /** Guardar la imagen el servidor */
        $image->save(CARPETA_IMAGENES . $nombreImagen);

        /** Guardar en la BD */
        $propiedad->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>

    <a href="/admin" class="botom boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">

        <?php include '../../includes/templates/formulario_propiedades.php'; ?>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>