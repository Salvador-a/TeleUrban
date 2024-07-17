<h2 class="teleurban__heading"><?php echo $titulo; ?></h2>
<p class="teleurban__descripcion">Elige el mejor horario para tu entrevista</p>

<div class="indicador-progreso">
    <div class="indicador-paso" id="paso1">1</div>
    <div class="barra-progreso">
        <div class="progreso" id="progreso-paso1"></div>
    </div>
    <div class="indicador-paso" id="paso2">2</div>
    <div class="barra-progreso">
        <div class="progreso" id="progreso-paso2"></div>
    </div>
</div>

<div class="formulario-contenedor">
    <div class="contenedor-formulario">
        <h1 class="contenedor-formulario__titulo" id="titulo-formulario">Contacto</h1>
        <form class="formulario" id="formulario-contacto" method="POST" enctype="multipart/form-data" action="/citas" aria-labelledby="titulo-formulario">
            <input type="hidden" name="confirmado" value="false">

            <div class="formulario__pagina formulario__pagina--activa" id="pagina1" role="tabpanel" aria-labelledby="titulo-datos">
                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda" id="titulo-datos">Tus Datos</legend>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="nombre">Nombre:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">person</i>
                            <input class="formulario__entrada" type="text" id="nombre" name="nombre" placeholder="Ingresa nombre" value="<?php echo $entrevista->nombre; ?>" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="a_paterno">A. Paterno:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">person</i>
                            <input class="formulario__entrada" type="text" id="a_paterno" name="a_paterno" placeholder="Ingresa apellido paterno" value="<?php echo $entrevista->a_paterno; ?>" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="a_materno">A. Materno:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">person</i>
                            <input class="formulario__entrada" type="text" id="a_materno" name="a_materno" placeholder="Ingresa apellido materno" value="<?php echo $entrevista->a_materno; ?>" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="email">E-mail:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">email</i>
                            <input class="formulario__entrada" type="email" id="email" name="email" placeholder="Ingresa email" value="<?php echo $entrevista->email; ?>" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="telefono">Teléfono:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">phone</i>
                            <input class="formulario__entrada" type="tel" id="telefono" name="telefono" placeholder="Ingresa teléfono" value="<?php echo $entrevista->telefono; ?>" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="discapacidad_id">Discapacidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">accessibility</i>
                            <select class="formulario__seleccion" id="discapacidad_id" name="discapacidad_id" aria-required="true">
                                <option value="">- Seleccione una Discapacidad -</option>
                                <?php foreach ($discapacidades as $discapacidad) { ?>
                                    <option value="<?php echo $discapacidad->id; ?>" <?php echo $entrevista->discapacidad_id == $discapacidad->id ? 'selected' : ''; ?>>
                                        <?php echo $discapacidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="genero_id">Género:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">wc</i>
                            <select class="formulario__seleccion" id="genero_id" name="genero_id" aria-required="true">
                                <option value="">- Seleccione un Género -</option>
                                <?php foreach ($generos as $genero) { ?>
                                    <option value="<?php echo $genero->id; ?>" <?php echo $entrevista->genero_id == $genero->id ? 'selected' : ''; ?>>
                                        <?php echo $genero->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="semestre_id">Semestre:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">school</i>
                            <select class="formulario__seleccion" id="semestre_id" name="semestre_id" aria-required="true">
                                <option value="">- Seleccione un Semestre -</option>
                                <?php foreach ($semestres as $semestre) { ?>
                                    <option value="<?php echo $semestre->id; ?>" <?php echo $entrevista->semestre_id == $semestre->id ? 'selected' : ''; ?>>
                                        <?php echo $semestre->grado; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="universidad_id">Universidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">account_balance</i>
                            <select class="formulario__seleccion" id="universidad_id" name="universidad_id" aria-required="true">
                                <option value="">- Seleccione una Universidad -</option>
                                <?php foreach ($universidades as $universidad) { ?>
                                    <option value="<?php echo $universidad->id; ?>" <?php echo $entrevista->universidad_id == $universidad->id ? 'selected' : ''; ?>>
                                        <?php echo $universidad->nombre; ?>
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


                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="curriculum">Curriculum (PDF):</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">description</i>
                            <input class="formulario__entrada" type="file" id="curriculum" name="curriculum" accept="application/pdf" aria-required="true">
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <button type="button" class="formulario__boton formulario__boton--siguiente" onclick="mostrarPagina(2)" aria-controls="pagina2" aria-expanded="false">Siguiente</button>
                </fieldset>
            </div>

            <div class="formulario__pagina" id="pagina2" role="tabpanel" aria-labelledby="titulo-cita">
                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda" id="titulo-cita">Cita</legend>

                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="fecha_hora">Fecha y Hora:</label>
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
                        <label class="formulario__etiqueta" for="modalidad_id">Modalidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons" aria-hidden="true">laptop</i>
                            <select class="formulario__seleccion" id="modalidad_id" name="modalidad_id" aria-required="true">
                                <option value="">- Seleccione una Modalidad -</option>
                                <?php foreach ($modalidades as $modalidad) { ?>
                                    <option value="<?php echo $modalidad->id; ?>" <?php echo $entrevista->modalidad_id == $modalidad->id ? 'selected' : ''; ?>>
                                        <?php echo $modalidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="icono-validacion"></span>
                        </div>
                    </div>

                    <button type="button" class="formulario__boton formulario__boton--anterior" onclick="mostrarPagina(1)" aria-controls="pagina1" aria-expanded="false">Anterior</button>
                    <input class="formulario__boton formulario__boton--agendar" id="boton-actualizar" type="button" value="Agendar" aria-controls="formulario-contacto">
                </fieldset>
            </div>
        </form>
    </div>
</div>