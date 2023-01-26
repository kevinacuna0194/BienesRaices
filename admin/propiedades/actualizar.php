<?php
require '../../includes/config/database.php';
require '../../includes/funciones.php';

/** Validar que sea un ID válido */
$id = $_GET['id'];
//Reescribir variable
$id = filter_var($id, FILTER_VALIDATE_INT);

// var_dump($id); /** int(4) / bool(false)*/

if (!$id) {
    header('location: /admin');
}

/** Conectarse a la BD */
$db = conectarDB();

$consulta = "SELECT * FROM propiedades WHERE id = $id";
$resultado = mysqli_query($db, $consulta);
/** Como es solo un resultado no necesitamos while() */
$propiedad = mysqli_fetch_assoc($resultado);

/** Consultar para obtener los vendedores **/
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

/** Arreglo con mensajes de errores **/
$errores = [];

// debuguear($_SERVER);
// debuguear($_SERVER["REQUEST_METHOD"]); /* string(3) "GET" string(4) "POST". Si visitas una URL es GET, pero cuando envías datos y especificas en el formulario que va a ser el tipo post, entonces se mandan como type post. */

$titulo = $propiedad['titulo'];
$precio = $propiedad['precio'];
$descripcion = $propiedad['descripcion'];
$habitaciones = $propiedad['habitaciones'];
$wc = $propiedad['wc'];
$estacionamiento = $propiedad['estacionamiento'];
$vendedorId = $propiedad['vendedorId'];
$imagenPropiedad = $propiedad['imagen'];

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
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo $titulo ?>">

            <label for=" precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio ?>">

            <label for=" imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

            <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small">

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

            <select name="vendedor">
                <option value="">-- Seleccione --</option>
                <?php while ($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                    <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?></option>
                <?php endwhile ?>
            </select>
        </fieldset>

        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>