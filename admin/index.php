<?php
include '../includes/app.php';

estaAutenticado();

/** Importar Clase */

use App\Propiedad;

/** Implementar un Método para obtener las Propiedades */
$propiedades = Propiedad::all();

/** Mostrar mensaje condicional */
$resultado = $_GET['resultado'] ?? null; // isset($_GET['resultado'])

/** Eliminar registro **/
/** debuguear($_POST);
    array(1) {
    ["id"]=>
    string(1) "4"
    } 
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {
        $propiedad = Propiedad::find($id);

        $propiedad->eliminar();
    }
}

/** Incluir template */
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio Creado Correctamente</p>

    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Anuncio Actualizado Correctamente</p>

    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Anuncio Eliminado Correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="botom boton-verde">Nueva Propiedad</a>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> <!-- 4- Mostrar los resultados -->
            <?php foreach ($propiedades as $propiedad) : ?>
                <tr>
                    <td> <?php echo $propiedad->id; ?> </td>
                    <td> <?php echo $propiedad->titulo; ?> </td>
                    <td> <img src="/imagenes/<?php echo $propiedad->imagen; ?>" alt="Imagen Tabla" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad->precio; ?> </td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
/** 5- Cerrar la conexión */
mysqli_close($db);

incluirTemplate('footer');
?>