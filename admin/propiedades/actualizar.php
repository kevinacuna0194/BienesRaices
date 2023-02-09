<?php

/** Importar Clase */

use App\Propiedad;
use App\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

require '../../includes/app.php';

estaAutenticado();

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT); //Reescribir variable

/** Obtener los datos de la propiedad */
$propiedad = Propiedad::find($id);

/*
debuguear($propiedad);
object(App\Propiedad)#5 (10) {
  ["id"]=>
  string(1) "5"
  ["titulo"]=>
  string(20) "The Witanhurst House"
  ["precio"]=>
  string(9) "150000.00"
  ["imagen"]=>
  string(36) "63e632e839c7f2cca3c9e68b1a606597.jpg"
  ["descripcion"]=>
  string(362) "In suscipit vestibulum mauris, eget venenatis urna. Sed ullamcorper faucibus felis et pharetra. Vestibulum condimentum tortor ac sodales ullamcorper. Nunc neque dolor, luctus non nibh ac, pretium ultrices leo. Curabitur sed eros augue. Suspendisse non eros ligula. Nam vitae semper enim. Vivamus pulvinar molestie tristique. Integer nec nulla hendrerit, rhoncus."
  ["habitaciones"]=>
  string(1) "2"
  ["wc"]=>
  string(1) "2"
  ["estacionamiento"]=>
  string(1) "2"
  ["creado"]=>
  string(10) "2023-01-26"
  ["vendedorId"]=>
  string(1) "1"
}
*/

/** Consultar para obtener los vendedores **/
$vendedores = Vendedor::all();

/** Arreglo con mensajes de errores **/
$errores = Propiedad::getErrores();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    /*
    debuguear($_POST);
    /*
        array(1) {
    ["propiedad"]=>
    array(6) {
        ["titulo"]=>
        string(20) "The Witanhurst House"
        ["precio"]=>
        string(9) "150000.00"
        ["descripcion"]=>
        string(362) "In suscipit vestibulum mauris, eget venenatis urna. Sed ullamcorper faucibus felis et pharetra. Vestibulum condimentum tortor ac sodales ullamcorper. Nunc neque dolor, luctus non nibh ac, pretium ultrices leo. Curabitur sed eros augue. Suspendisse non eros ligula. Nam vitae semper enim. Vivamus pulvinar molestie tristique. Integer nec nulla hendrerit, rhoncus."
        ["habitaciones"]=>
        string(1) "2"
        ["wc"]=>
        string(1) "2"
        ["estacionamiento"]=>
        string(1) "2"
    }
    }
    */

    /** Asignar los atributos */
    $args = $_POST['propiedad'];
    $propiedad->sincronizar($args);

    /** Vlidar formulario */
    $errores = $propiedad->validar();

    /** Subida de archivos */
    /** Generar un nombre único */
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        /** Setear la imagen */
        /** Realiza un resize a la imagen con Intervention */
        $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    /** revisar que el Arreglo de errores, este vacio **/
    if (empty($errores)) {
        /** Si hay una imagen almacenarla */
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            $image->save(CARPETA_IMAGENES . $nombreImagen);
        }

        $propiedad->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Propiedad</h1>

    <a href="/admin" class="botom boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="" enctype="multipart/form-data">

        <?php include '../../includes/templates/formulario_propiedades.php'; ?>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>