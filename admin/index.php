<?php

session_start();

/*
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
/*
array(2) {
  ["usuario"]=>
  string(17) "correo@correo.com"
  ["login"]=>
  bool(true)
}
*/

/** Leer la variable de $_SESSION */
$auth = $_SESSION['login'];

if (!$auth) {
    header('location: /');
}

/** 1- Importar la conexión */
include '../includes/config/database.php';
$db = conectarDB();

/** 2- Escribir el Query */
$query = "SELECT * FROM propiedades";

/** 3- Consultar la BD */
$consulta = mysqli_query($db, $query);

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
        /** Ya tenemos una conexión a la BD */

        /** Eliminar el archivo */
        $query = "SELECT imagen FROM propiedades WHERE id = $id";
        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);

        /* var_dump($imagen); array(1) { ["imagen"]=> string(36) "7dece554ecfe0d3a85074622e9db1ecf.jpg" } */
        unlink('../imagenes/' . $propiedad['imagen']);

        /** Elimina la propiedad */
        $query = "DELETE FROM propiedades WHERE id = $id";

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            header('location: /admin?resultado=3');
        }
    }
}

/** Incluir template */
include '../includes/funciones.php';
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
            <?php while ($propiedad = mysqli_fetch_assoc($consulta)) : ?>
                <tr>
                    <td> <?php echo $propiedad['id']; ?> </td>
                    <td> <?php echo $propiedad['titulo']; ?> </td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="Imagen Tabla" class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad['precio']; ?> </td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>

                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php
/** 5- Cerrar la conexión */
mysqli_close($db);

incluirTemplate('footer');
?>