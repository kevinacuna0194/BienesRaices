<?php

use App\Propiedad;

require '../../includes/app.php';
estaAutenticado();

/** BD */
$db = conectarDB();

/** Consultar para obtener los vendedores **/
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

/** Arreglo con mensajes de errores **/
$errores = [];

// debuguear($_SERVER);
// debuguear($_SERVER["REQUEST_METHOD"]); /* string(3) "GET" string(4) "POST". Si visitas una URL es GET, pero cuando envías datos y especificas en el formulario que va a ser el tipo post, entonces se mandan como type post. */

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $propiedad = new Propiedad($_POST);
    /** Objeto con la instancia a la Clase */

    $propiedad->guardar();

    debuguear($propiedad);
    /*
    object(App\Propiedad)#6 (10) {
    ["id"]=>
    string(0) ""
    ["titulo"]=>
    string(9) "Neverland"
    ["precio"]=>
    string(6) "100000"
    ["imagen"]=>
    string(0) ""
    ["descripcion"]=>
    string(424) "Vivamus at lectus sit amet nunc viverra viverra. Nunc libero magna, bibendum vitae erat nec, venenatis pharetra felis. Proin nunc diam, consectetur at tellus id, porta suscipit lacus. Aenean eu velit libero. Etiam at pharetra lacus. Praesent a lectus sit amet orci consectetur fermentum. Pellentesque commodo auctor ultrices. Maecenas urna purus, ullamcorper vel turpis tempor, lobortis feugiat nisl. Pellentesque porttitor."
    ["habitaciones"]=>
    string(0) ""
    ["wc"]=>
    string(1) "2"
    ["estacionamiento"]=>
    string(1) "3"
    ["creado"]=>
    string(0) ""
    ["vendedorId"]=>
    string(0) ""
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

    /** debuguear($imagen['name']); string(12) "anuncio1.jpg" / string(0) ""
     En caso de que este valor exista, significa que el usuario se subió una imagen, en caso de que esté vacío significa que no subió nada. */
    if (!$imagen['name'] || $imagen['error']) { // 2mb por default en php. retorna size 0 y errror 1.
        $errores[] = 'La imagen es obligatoria';
    }

    /** Validar por tamaño (1 MB máximo ~ 1000 KB ~ 100.000.000 bytes) */
    $medida = 1000 * 1000;

    if ($imagen['size'] > $medida) {
        $errores[] = 'La imagen es muy pesada';
    }

    /** revisar que el Arrat de errores este vacio **/
    if (empty($errores)) {

        /** Subida de archivos **/
        /** 1- Crear carpeta */
        $carpetaImagenes = '../../imagenes/';

        if (!is_dir($carpetaImagenes)) {
            mkdir($carpetaImagenes);
        }

        /** 2- Generar un nombre único */
        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

        /** 3- Subir la imagen 
         * function move_uploaded_file(string $from, string $to): bool
         */
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            /** Redireccionar al usuario */
            header('location: /admin?resultado=1');
        }
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
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo $titulo ?>">

            <label for=" precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio ?>">

            <label for=" imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"><?php echo $descripcion ?></textarea>
        </fieldset>

        <fielset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo $habitaciones ?>"> <!-- step="2 -->

            <label for="wc">Baño:</label>
            <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo $wc ?>">

            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo $estacionamiento ?>">
        </fielset>

        <fieldset>
            <legend>Vendedor</legend>

            <select name="vendedorId">
                <option value="">-- Seleccione --</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>