<?php

require '../../includes/app.php';
use App\Vendedor;

estaAutenticado();

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('location: /admin');
}

/** Obtener el arreglo del vendedor desde la BD */
$vendedor = Vendedor::find($id);

$errores = Vendedor::getErrores();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    /** Asignar los valores */
    $args = $_POST['vendedor'];

    /** Sicronizar Objeto en Memoria*/
    $vendedor->sincronizar($args);

    /** Validar */
    $errores = $vendedor->validar();

    if (empty($errores)) {
        $vendedor->guardar();
    }
}

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1>Actualizar Vendedor(a)</h1>

    <a href="/admin" class="botom boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" action="">

        <?php include '../../includes/templates/formulario_vendedores.php'; ?>

        <input type="submit" value="Guardar Cambios" class="boton boton-verde">
    </form>
</main>

<?php
incluirTemplate('footer');
?>