<?php
require '../../includes/config/database.php';
require '../../includes/funciones.php';

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
    // debuguear($_POST);
    /*
    array(2) {
    ["titulo"]=>
    string(16) "Casa en la playa"
    ["precio"]=>
    string(5) "10000"
    }
    */

    $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
    $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
    $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
    $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
    $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
    $estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento'] );
    $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
    $creado = date('Y/m/d');

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
        $query = " INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, creado, vendedorId) VALUES ('$titulo', '$precio', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId') ";

        // echo $query;

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            /** Redireccionar al usuario */
            header('location: /admin');
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