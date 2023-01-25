<?php

$resultado = $_GET['resultado'] ?? null; // isset($_GET['resultado'])

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

        <tbody>
            <tr>
                <td>1</td>
                <td>Casa en la playa</td>
                <td> <img src="../imagenes/0ac34ab5543d74fa192df2f73b264819.jpg" alt="Imagen Tabla" class="imagen-tabla"> </td>
                <td>120000</td>
                <td>
                    <a href="#" class="boton-rojo-block">Eliminar</a>
                    <a href="#" class="boton-amarillo-block">Actualizar</a>
                </td>
            </tr>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>