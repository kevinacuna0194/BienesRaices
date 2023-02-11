<?php
include '../includes/app.php';

estaAutenticado();

/** Importar Clase */

use App\Propiedad;
use App\Vendedor;

/** Implementar un Método para obtener las Propiedades */
$propiedades = Propiedad::all();
$vendedores = Vendedor::all();

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
    /*
    debuguear($_POST);
    /*
    array(2) {
    ["id"]=>
    string(2) "36"
    ["tipo"]=>
    string(9) "propiedad"
    }

    array(2) {
    ["id"]=>
    string(1) "2"
    ["tipo"]=>
    string(8) "vendedor"
    }
    */

    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if ($id) {

        $tipo = $_POST['tipo'];

        if (validarTipoContenido($tipo)) {
            /** Compara lo que vamos a eliminar */
            if ($tipo === 'vendedor') {
                $vendedor = Vendedor::find($id);
                $vendedor->eliminar();
            } else if ($tipo === 'propiedad') {
                $propiedad = Propiedad::find($id);
                $propiedad->eliminar();
            }
        }
    }
}

/** Incluir template */
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Creado Correctamente</p>

    <?php elseif (intval($resultado) === 2) : ?>
        <p class="alerta exito">Actualizado Correctamente</p>

    <?php elseif (intval($resultado) === 3) : ?>
        <p class="alerta exito">Eliminado Correctamente</p>
    <?php endif; ?>

    <a href="/admin/propiedades/crear.php" class="botom boton-verde">Nueva Propiedad</a>
    <a href="/admin/vendedores/crear.php" class="botom boton-amarillo">Nuevo(a) Vendedor</a>

    <!-- PROPIEDADES -->
    <h2>Propiedades</h2>

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
                            <input type="hidden" name="tipo" value="propiedad">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- VENDEDORES -->
    <h2>Vendedores</h2>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody> <!-- 4- Mostrar los resultados -->
            <?php foreach ($vendedores as $vendedor) : ?>
                <tr>
                    <td> <?php echo $vendedor->id; ?> </td>
                    <td> <?php echo $vendedor->nombre . " " . $vendedor->apellido; ?> </td>
                    <td> <?php echo $vendedor->telefono; ?> </td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $vendedor->id; ?>">
                            <input type="hidden" name="tipo" value="vendedor">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="admin/vendedores/actualizar.php?id=<?php echo $vendedor->id; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>