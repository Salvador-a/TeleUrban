<h2 class="teleurban__heading"><?php echo $titulo; ?></h2>
<p class="teleurban__descripcion">Elige el mejor horario para tu entrevista</p>

<div class="formulario-contenedor">
    <div class="contenedor-formulario">
        <h1 class="contenedor-formulario__titulo" id="titulo-formulario">Editar Cita</h1>
        <?php include_once __DIR__ . '/../../templates/alertas.php'; ?>

        <form class="formulario" id="formulario-editar" method="POST" enctype="multipart/form-data" action="/modificar-cita?id=<?php echo $entrevista->id; ?>" aria-labelledby="titulo-formulario">
            <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">

            <fieldset class="formulario__seccion">
                <legend class="formulario__leyenda" id="titulo-cita">Editar Cita</legend>

                <div class="formulario__campo">
    <label class="formulario__etiqueta" for="fecha_hora">Fecha y Hora:
        <span class="tooltip-icon">
            <i class="material-icons" aria-hidden="true">lightbulb_outline</i>
            <span class="tooltip">
                <strong>Días y Horas Válidas:</strong>
                <ul>
                    <li>Lunes - Viernes: 10:00 AM - 04:00 PM</li>
                </ul>
            </span>
        </span>
    </label>
    <div class="formulario__input-con-icono">
        <i class="material-icons" aria-hidden="true">event</i>
        <input class="formulario__entrada" type="text" id="fecha_hora" name="fecha_hora" placeholder="Selecciona fecha y hora" value="<?php echo $entrevista->fecha_hora; ?>" aria-required="true">
        <span class="icono-validacion"></span>
    </div>
</div>


                <div class="formulario__campo">
                    <label class="formulario__etiqueta" for="departamento_id">Área:</label>
                    <div class="formulario__input-con-icono">
                        <i class="material-icons" aria-hidden="true">business_center</i>
                        <select class="formulario__seleccion" id="departamento_id" name="departamento_id" aria-required="true">
                            <option value="">- Seleccione un Área -</option>
                            <?php foreach ($departamentos as $departamento) { ?>
                                <option value="<?php echo $departamento->id; ?>" <?php echo $entrevista->departamento_id == $departamento->id ? 'selected' : ''; ?>>
                                    <?php echo $departamento->nombre_departamento; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <span class="icono-validacion"></span>
                    </div>
                </div>

                <div class="formulario__campo">
                    <label for="tags_input" class="formulario__etiqueta">Áreas de Experiencia (separadas por coma)
                        <span class="tooltip-icon">
                            <i class="material-icons" aria-hidden="true">lightbulb_outline</i>
                            <span class="tooltip">Cada habilidad que pongas debe estar separada por coma. Si deseas eliminar alguna de las tags, solo debes darle doble clic en la tag que desees eliminar.</span>
                        </span>
                    </label>
                    <div class="formulario__input-con-icono">
                        <i class="material-icons" aria-hidden="true">star</i>
                        <input type="text" class="formulario__entrada" id="tags_input" name="tags_input" placeholder="Ej. Comunicación, Trabajo en equipo, Liderazgo" value="<?php echo htmlspecialchars($entrevista->tags ?? ''); ?>" aria-required="true">
                        <span class="icono-validacion"></span>
                    </div>
                    <div id="tags" class="formulario__listado"></div>
                    <input type="hidden" name="tags" value="<?php echo htmlspecialchars($entrevista->tags ?? ''); ?>">
                </div>

                <div class="formulario__campo">
                    <label class="formulario__etiqueta" for="habilidades">Habilidades:</label>
                    <div class="formulario__input-con-icono formulario__input-con-icono--grande">
                        <i class="material-icons" aria-hidden="true">build</i>
                        <textarea class="formulario__entrada formulario__entrada--grande" id="habilidades" name="habilidades" placeholder="Describe tus habilidades" aria-required="true"><?php echo $entrevista->habilidades; ?></textarea>
                        <span class="icono-validacion"></span>
                    </div>
                </div>

                <input class="formulario__boton formulario__boton--actualizar" id="boton-actualizar" type="submit" value="Actualizar" aria-controls="formulario-editar">
            </fieldset>
        </form>
    </div>
</div>
