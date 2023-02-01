<fieldset>
    <legend>Información General</legend>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo s($propiedad->titulo); ?>">

    <label for=" precio">Precio:</label>
    <input type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo s($propiedad->precio); ?>">

    <label for=" imagen">Imagen:</label>
    <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">

    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion"><?php echo s($propiedad->descripcion); ?></textarea>
</fieldset>

<fielset>
    <legend>Información Propiedad</legend>

    <label for="habitaciones">Habitaciones:</label>
    <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" min="1" max="9" value="<?php echo s($propiedad->habitaciones); ?>"> <!-- step="2 -->

    <label for="wc">Baño:</label>
    <input type="number" id="wc" name="wc" placeholder="Ej: 3" min="1" max="9" value="<?php echo s($propiedad->wc); ?>">

    <label for="estacionamiento">Estacionamiento</label>
    <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" min="1" max="9" value="<?php echo s($propiedad->estacionamiento); ?>">
</fielset>

<fieldset>
    <legend>Vendedor</legend>

</fieldset>