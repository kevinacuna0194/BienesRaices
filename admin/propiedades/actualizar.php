<?php
require '../../includes/app.php';

/** Importar Clase */

use App\Propiedad;

estaAutenticado();

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT); //Reescribir variable

$propiedad = Propiedad::find($id);

/*
debuguear($propiedad);
/*
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
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

/** Arreglo con mensajes de errores **/
$errores = [];

// debuguear($_SERVER);
// debuguear($_SERVER["REQUEST_METHOD"]); /* string(3) "GET" string(4) "POST". Si visitas una URL es GET, pero cuando envías datos y especificas en el formulario que va a ser el tipo post, entonces se mandan como type post. */

$titulo = $propiedad->titulo;
$precio = $propiedad->precio;
$descripcion = $propiedad->descripcion;
$habitaciones = $propiedad->habitaciones;
$wc = $propiedad->wc;
$estacionamiento = $propiedad->estacionamiento;
$vendedorId = $propiedad->vendedorId;
$imagenPropiedad = $propiedad->imagen;

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    // debuguear($_POST);
    /*
    array(2) {
    ["titulo"]=>
    string(16) "Casa en la playa"
    ["precio"]=>
    string(5) "10000"
    }
    */

    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string($db, $_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['estacionamiento']);
    $vendedorId = mysqli_real_escape_string($db, $_POST['vendedor']);
    $creado = date('Y/m/d');

    /** Asignar $_FILES hacia una variable */
    $imagen = $_FILES['imagen'];

    // debuguear($imagen);
    /*
    array(6) {
    ["name"]=>
    string(12) "anuncio1.jpg"
    ["full_path"]=>
    string(12) "anuncio1.jpg"
    ["type"]=>
    string(10) "image/jpeg"
    ["tmp_name"]=>
    string(45) "C:\Users\kevin\AppData\Local\Temp\php3016.tmp"
    ["error"]=>
    int(0)
    ["size"]=>
    int(94804)
    }
    */

    if (!$titulo) {
        $errores[] = 'Debes añadir un Título';
    }

    if (!$precio) {
        $errores[] = 'El Precio es obligatorio';
    }

    if (strlen($descripcion) < 50) {
        $errores[] = 'La Descripcion es obligatoria y debe tener al menos 50 caracteres';
    }

    if (!$habitaciones) {
        $errores[] = 'El número de Habitaciones es obligatoria';
    }

    if (!$wc) {
        $errores[] = 'El número de Baños es obligatorio';
    }

    if (!$estacionamiento) {
        $errores[] = 'El número de lugares de Estacionamiento es obligatorio';
    }

    if (!$vendedorId) {
        $errores[] = 'Elige un Vendedor';
    }

    /** No es obligatorio subir una nueva imagen en actualizar **/
    /* if (!$imagen['name'] || $imagen['error']) { // 2mb por default en php. retorna size 0 y errror 1.
        $errores[] = 'La imagen es obligatoria';
    } */

    /** Validar por tamaño (1 MB máximo ~ 1000 KB ~ 100.000.000 bytes) */
    $medida = 1000 * 1000;
    if ($imagen['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }

    /** revisar que el Arrat de errores este vacio **/
    if (empty($errores)) {

        /*** Subida de archivos ***/
        /** 1- Crear carpeta */
        /* debuguear($imagen); 
        *array(6) {
        ["name"]=>
        string(12) "anuncio1.jpg"
        ["full_path"]=>
        string(12) "anuncio1.jpg"
        ["type"]=>
        string(10) "image/jpeg"
        ["tmp_name"]=>
        string(45) "C:\Users\kevin\AppData\Local\Temp\phpFD4D.tmp"
        ["error"]=>
        int(0)
        ["size"]=>
        int(94804)
        } - si esxiste es porque se subio una nueva imagen */

        /** Crear carpeta */
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        $nombreImagen = '';

        if ($imagen['name']) { // En caso de que subamos una nueva imagen.
            /* Eliminar imagen previa */
            unlink($carpetaImagenes . $propiedad['imagen']);

            /** Generar un nombre único */
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            /** Subir la imagen */
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);
        } else {
            $nombreImagen = $propiedad['imagen'];
        }

        /** Insertan en la BD */
        $query = " UPDATE propiedades SET titulo = '$titulo', precio = '$precio', imagen = '$nombreImagen', descripcion = '$descripcion', habitaciones = $habitaciones, wc = $wc, estacionamiento = $estacionamiento, creado = '$creado', vendedorId = $vendedorId WHERE id = $id";

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            /** Redireccionar al usuario */
            header('location: /admin?resultado=2');
        }
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