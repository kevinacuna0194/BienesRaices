<?php

require '../../includes/config/database.php';
require '../../includes/funciones.php';

/** BD */
$db = conectarDB();

// debuguear($_SERVER);
// debuguear($_SERVER["REQUEST_METHOD"]); /* string(3) "GET" string(4) "POST". Si visitas una URL es GET, pero cuando envías datos y especificas en el formulario que va a ser el tipo post, entonces se mandan como type post. */

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    debuguear($_POST);
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
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Crear</h1>

    <a href="/admin" class="botom boton-verde">Volver</a>

    <form class="formulario" method="POST" action="/admin/propiedades/crear.php">
        <fieldset>
            <legend>Información General</legend>

            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad">

            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" placeholder="Precio Propiedad">

            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" accept="image/jpeg, image/png">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion"></textarea>
        </fieldset>

        <fielset>
            <legend>Información Propiedad</legend>

            <label for="habitaciones">Habitaciones:</label>
            <input type="number" id="habitaciones" placeholder="Ej: 3" min="1" max="9"> <!-- step="2 -->

            <label for="wc">Baño:</label>
            <input type="number" id="wc" placeholder="Ej: 3" min="1" max="9">

            <label for="estacionamiento">Estacionamiento</label>
            <input type="number" id="estacionamiento" placeholder="Ej: 3" min="1" max="9">
        </fielset>

        <fieldset>
            <legend>Vendedor</legend>

            <select>
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