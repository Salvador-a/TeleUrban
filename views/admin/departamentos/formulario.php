<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Departamento</legend>

    <div class="formulario__campo">
        <label for="nombre_departamento" class="formulario__label">Nombre Departamento</label>
        <input
            type="text"
            class="formulario__input"
            id="nombre_departamento"
            name="nombre_departamento"
            placeholder="Nombre del Departamento"
            value="<?php echo $departamento->nombre_departamento ?? ''; ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="id_encargado" class="formulario__label">Jefe del Departamento</label>
        <select 
            id="id_encargado" 
            name="id_encargado" 
            class="formulario__input formulario__input--select"
        >
            <option value="">-- Seleccionar --</option>
            <?php foreach($empleados as $empleado) { ?>
                <option value="<?php echo $empleado->id; ?>" <?php echo $departamento->id_encargado == $empleado->id ? 'selected' : ''; ?>>
                    <?php echo $empleado->nombre . " " . $empleado->apellido . ' - ' . $empleado->puesto_trabajo; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input
            type="file"
            class="formulario__input formulario__input--file"
            id="imagen"
            name="imagen"
            value="<?php echo $_POST['imagen'] ?? ''; ?>"
        >
    </div>

    <?php if(isset($departamento->imagen_actual)) { ?>
        <p class="formulario__texto">Imagen Actual:</p>
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/galeria/' . $departamento->imagen_actual; ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/galeria/' . $departamento->imagen_actual; ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/galeria/' . $departamento->imagen_actual; ?>.png" alt="Imagen departamento">
            </picture>
        </div>
    <?php } ?>

    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Descripción</label>
        <textarea
            class="formulario__input"
            id="descripcion"
            name="descripcion"
            placeholder="Descripción del Departamento"
            rows="8"
        ><?php echo $departamento->descripcion ?? ''; ?></textarea>
    </div>
</fieldset>
