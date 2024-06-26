<div class="formulario-contenedor">
    <div class="contenedor-formulario">
        <h1 class="contenedor-formulario__titulo">Contacto</h1>
        <form class="formulario" id="formulario-contacto" method="POST" enctype="multipart/form-data" action="/citas">
            <input type="hidden" name="confirmado" value="false">
            <div class="formulario__pagina formulario__pagina--activa" id="pagina1">
                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda">Tus Datos</legend>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="nombre">Nombre:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">person</i>
                            <input class="formulario__entrada" type="text" id="nombre" name="nombre" placeholder="Ingresa nombre" value="<?php echo $entrevista->nombre; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="a_paterno">A. Paterno:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">person</i>
                            <input class="formulario__entrada" type="text" id="a_paterno" name="a_paterno" placeholder="Ingresa apellido paterno" value="<?php echo $entrevista->a_paterno; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="a_materno">A. Materno:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">person</i>
                            <input class="formulario__entrada" type="text" id="a_materno" name="a_materno" placeholder="Ingresa apellido materno" value="<?php echo $entrevista->a_materno; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="email">E-mail:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">email</i>
                            <input class="formulario__entrada" type="email" id="email" name="email" placeholder="Ingresa email" value="<?php echo $entrevista->email; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="telefono">Teléfono:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">phone</i>
                            <input class="formulario__entrada" type="tel" id="telefono" name="telefono" placeholder="Ingresa teléfono" value="<?php echo $entrevista->telefono; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="discapacidad_id">Discapacidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">accessibility</i>
                            <select class="formulario__seleccion" id="discapacidad_id" name="discapacidad_id">
                                <option value="">- Seleccione una Discapacidad -</option>
                                <?php foreach ($discapacidades as $discapacidad) { ?>
                                    <option value="<?php echo $discapacidad->id; ?>" <?php echo $entrevista->discapacidad_id == $discapacidad->id ? 'selected' : ''; ?>>
                                        <?php echo $discapacidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="genero_id">Género:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">wc</i>
                            <select class="formulario__seleccion" id="genero_id" name="genero_id">
                                <option value="">- Seleccione un Género -</option>
                                <?php foreach ($generos as $genero) { ?>
                                    <option value="<?php echo $genero->id; ?>" <?php echo $entrevista->genero_id == $genero->id ? 'selected' : ''; ?>>
                                        <?php echo $genero->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="semestre_id">Semestre:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">school</i>
                            <select class="formulario__seleccion" id="semestre_id" name="semestre_id">
                                <option value="">- Seleccione un Semestre -</option>
                                <?php foreach ($semestres as $semestre) { ?>
                                    <option value="<?php echo $semestre->id; ?>" <?php echo $entrevista->semestre_id == $semestre->id ? 'selected' : ''; ?>>
                                        <?php echo $semestre->grado; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="universidad_id">Universidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">account_balance</i>
                            <select class="formulario__seleccion" id="universidad_id" name="universidad_id">
                                <option value="">- Seleccione una Universidad -</option>
                                <?php foreach ($universidades as $universidad) { ?>
                                    <option value="<?php echo $universidad->id; ?>" <?php echo $entrevista->universidad_id == $universidad->id ? 'selected' : ''; ?>>
                                        <?php echo $universidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="habilidades">Habilidades:</label>
                        <div class="formulario__input-con-icono formulario__input-con-icono--grande">
                            <i class="material-icons">build</i>
                            <textarea class="formulario__entrada formulario__entrada--grande" id="habilidades" name="habilidades" placeholder="Describe tus habilidades"><?php echo $entrevista->habilidades; ?></textarea>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="curriculum">Curriculum (PDF):</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">description</i>
                            <input class="formulario__entrada" type="file" id="curriculum" name="curriculum" accept="application/pdf">
                        </div>
                    </div>
                    <button type="button" class="formulario__boton formulario__boton--siguiente" onclick="mostrarPagina(2)">Siguiente</button>
                </fieldset>
            </div>
            
            <div class="formulario__pagina" id="pagina2">
                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda">Cita</legend>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="fecha_hora">Fecha y Hora:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">event</i>
                            <input class="formulario__entrada" type="text" id="fecha_hora" name="fecha_hora" placeholder="Selecciona fecha y hora" value="<?php echo $entrevista->fecha_hora; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="departamento_id">Área:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">business_center</i>
                            <select class="formulario__seleccion" id="departamento_id" name="departamento_id">
                                <option value="">- Seleccione un Área -</option>
                                <?php foreach ($departamentos as $departamento) { ?>
                                    <option value="<?php echo $departamento->id; ?>" <?php echo $entrevista->departamento_id == $departamento->id ? 'selected' : ''; ?>>
                                        <?php echo $departamento->nombre_departamento; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="modalidad_id">Modalidad:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">laptop</i>
                            <select class="formulario__seleccion" id="modalidad_id" name="modalidad_id">
                                <option value="">- Seleccione una Modalidad -</option>
                                <?php foreach ($modalidades as $modalidad) { ?>
                                    <option value="<?php echo $modalidad->id; ?>" <?php echo $entrevista->modalidad_id == $modalidad->id ? 'selected' : ''; ?>>
                                        <?php echo $modalidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="formulario__boton formulario__boton--anterior" onclick="mostrarPagina(1)">Anterior</button>
                    <input class="formulario__boton formulario__boton--agendar" id="boton-agendar" type="button" value="Agendar">
                </fieldset>
            </div>
        </form>
    </div>
</div>
