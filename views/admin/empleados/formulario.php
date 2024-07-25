<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Personal</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type="text"
            class="formulario__input"
            id="nombre"
            name="nombre"
            placeholder="Nombre empleado"
            value="<?php echo s($empleado->nombre); ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido</label>
        <input
            type="text"
            class="formulario__input"
            id="apellido"
            name="apellido"
            placeholder="Apellido empleado"
            value="<?php echo s($empleado->apellido); ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="ciudad" class="formulario__label">Ciudad</label>
        <input
            type="text"
            class="formulario__input"
            id="ciudad"
            name="ciudad"
            placeholder="Ciudad"
            value="<?php echo s($empleado->ciudad); ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="pais" class="formulario__label">País</label>
        <input
            type="text"
            class="formulario__input"
            id="pais"
            name="pais"
            placeholder="País empleado"
            value="<?php echo s($empleado->pais); ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="email" class="formulario__label">Email</label>
        <input
            type="email"
            class="formulario__input"
            id="email"
            name="email"
            placeholder="Email empleado"
            value="<?php echo s($empleado->email); ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input
            type="file"
            class="formulario__input formulario__input--file"
            id="imagen"
            name="imagen"
        >
    </div>

    <?php if(isset($empleado->imagen_actual)) { ?>
        <p class="formulario__texto">Imagen Actual:</p>
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/galeria/' . s($empleado->imagen); ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/galeria/' . s($empleado->imagen); ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/galeria/' . s($empleado->imagen); ?>.png" alt="Imagen empleado">
            </picture>
        </div>
    <?php } ?>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Información Extra</legend>

    <div class="formulario__campo">
        <label for="puesto_trabajo_id" class="formulario__label">Puesto de Trabajo</label>
        <select class="formulario__select" id="puesto_trabajo_id" name="puesto_trabajo_id">
            <option value="">Seleccione un Puesto de Trabajo</option>
            <?php foreach($puestos_trabajo as $puesto) { ?>
                <option value="<?php echo s($puesto->id); ?>" <?php echo $empleado->puesto_trabajo_id === $puesto->id ? 'selected' : ''; ?>>
                    <?php echo s($puesto->nombre_puesto); ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="tags_input" class="formulario__label">Áreas de Experiencia (separadas por coma)</label>
        <input
            type="text"
            class="formulario__input"
            id="tags_input"
            name="tags_input"
            placeholder="Ej. Node.js, PHP, CSS, Laravel, UX / UI"
            value="<?php echo s($empleado->tags); ?>"
        >
        <div id="tags" class="formulario__listado"></div>
        <input 
            type="hidden" 
            name="tags" 
            value="<?php echo s($empleado->tags); ?>"> 
    </div>

    <div class="formulario__campo">
        <label for="departamento_id" class="formulario__label">Departamento</label>
        <select class="formulario__select" id="departamento_id" name="departamento_id">
            <option value="">Seleccione un Departamento</option>
            <?php foreach($departamentos as $departamento) { ?>
                <option value="<?php echo s($departamento->id); ?>" <?php echo $empleado->departamento_id === $departamento->id ? 'selected' : ''; ?>>
                    <?php echo s($departamento->nombre_departamento); ?>
                </option>
            <?php } ?>
        </select>
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes Sociales</legend>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input
                type="text"
                class="formulario__input--sociales"
                name="redes[facebook]"
                placeholder="Facebook"
                value="<?php echo s($redes_sociales->facebook ?? ''); ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-x-twitter"></i>
            </div>
            <input
                type="text"
                class="formulario__input--sociales"
                name="redes[twitter]"
                placeholder="Twitter (𝕏)"
                value="<?php echo s($redes_sociales->twitter ?? ''); ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input
                type="text"
                class="formulario__input--sociales"
                name="redes[instagram]"
                placeholder="Instagram"
                value="<?php echo s($redes_sociales->instagram ?? ''); ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-github"></i>
            </div>
            <input
                type="text"
                class="formulario__input--sociales"
                name="redes[github]"
                placeholder="GitHub"
                value="<?php echo s($redes_sociales->github ?? ''); ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fab fa-linkedin"></i>
            </div>
            <input
                type="text"
                class="formulario__input--sociales"
                name="redes[linkedin]"
                placeholder="LinkedIn"
                value="<?php echo s($redes_sociales->linkedin ?? ''); ?>"
            >
        </div>
    </div>
</fieldset>
