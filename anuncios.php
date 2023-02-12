<?php
require 'includes/app.php';
incluirTemplate('header');
?>

<main class="contenedor seccion">

    <h2>Casas y Departamentos en Venta</h2>

    <?php
    include 'includes/templates/anuncios.php';
    ?>

    </div> <!--.contenedor-anuncios-->
</main>

<?php
incluirTemplate('footer');
?>