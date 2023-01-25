<?php

/** 1- Importar la conexión */
include '../includes/config/database.php';
$db = conectarDB();

/** 2- Escribir el Query */
$query = "SELECT * FROM propiedades";

/** 3- Consultar la BD */
$consulta = mysqli_query($db, $query);

/** Mostrar mensajecondicional */
$resultado = $_GET['resultado'] ?? null; // isset($_GET['resultado'])

/** Incluir template */
include '../includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Administrador de Bienes Raices</h1>
    <?php if (intval($resultado) === 1) : ?>
        <p class="alerta exito">Anuncio creado correctamente</p>
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
                        <a href="#" class="boton-rojo-block">Eliminar</a>
                        <a href="#" class="boton-amarillo-block">Actualizar</a>
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