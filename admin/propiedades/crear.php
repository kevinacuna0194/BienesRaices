<?php

require '../../includes/app.php';

use App\Propiedad;
/* import the Intervention Image Manager Class */
use Intervention\Image\ImageManagerStatic as Image;

estaAutenticado();

/** BD */
$db = conectarDB();

/** Consultar para obtener los vendedores **/
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

/** Arreglo con mensajes de errores 
 * Para que no marque undefined **/
$errores = Propiedad::getErrores();

$titulo = '';
$precio = '';
$descripcion = '';
$habitaciones = '';
$wc = '';
$estacionamiento = '';
$vendedorId = '';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    /** Crear Objeto con la instancia a la Clase */
    $propiedad = new Propiedad($_POST);

    /** Subida de archivos **/
    /** 2- Generar un nombre único */
    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    if ($_FILES['imagen']['tmp_name']) {
        /** Setear la imagen */
        /** Realiza un resize a la imagen con Intervention */
        $image = Image::make($_FILES['imagen']['tmp_name'])->fit(800, 600);
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
        $resultado = $propiedad->guardar();

        /** Mensaje de éxito o error */
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