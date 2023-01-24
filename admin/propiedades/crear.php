<?php
require '../../includes/config/database.php';
require '../../includes/funciones.php';

/** BD */
$db = conectarDB();

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
    // debuguear($_POST);
    /*
    array(2) {
    ["titulo"]=>
    string(16) "Casa en la playa"
    ["precio"]=>
    string(5) "10000"
    }
    */

    $titulo = $_POST['titulo'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $habitaciones = $_POST['habitaciones'];
    $wc = $_POST['wc'];
    $estacionamiento = $_POST['estacionamiento'];
    $vendedorId = $_POST['vendedor'];

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

    /** revisar que el Arrat de errores este vacio **/
    if (empty($errores)) {
        /** Insertan en la BD */
        $query = " INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, vendedorId) VALUES ('$titulo', '$precio', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$vendedorId') ";

        // echo $query;

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            echo "Insertado Correctamnete";
        } else {
            echo "No se pudo insertar el registro";
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

    <form class="formulario" method="POST" action="">
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo $titulo ?>">

            <label for=" precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio ?>">

            <label for=" imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png">

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
                <option value="1">Kevin</option>
                <option value="2">Juan</option>
            </select>
        </fieldset>

        <input type="submit" value="Crear Propiedad" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>