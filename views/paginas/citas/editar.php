<!-- views/paginas/citas/editar.php -->
<div class="formulario-contenedor">
    <div class="contenedor-formulario">
        <h1 class="contenedor-formulario__titulo">Editar Cita</h1>
        <form class="formulario" id="formulario-contacto" method="POST" enctype="multipart/form-data" action="/modificar-cita?id=<?php echo $entrevista->id; ?>">
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
                        <label class="formulario__etiqueta" for="email">Email:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">email</i>
                            <input class="formulario__entrada" type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" value="<?php echo $entrevista->email; ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="telefono">Teléfono:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">phone</i>
                            <input class="formulario__entrada" type="tel" id="telefono" name="telefono" placeholder="Ingresa tu número de teléfono" value="<?php echo $entrevista->telefono; ?>">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda">Datos de la Cita</legend>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="fecha_hora">Fecha y Hora:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">date_range</i>
                            <input class="formulario__entrada" type="datetime-local" id="fecha_hora" name="fecha_hora" value="<?php echo date('Y-m-d\TH:i', strtotime($entrevista->fecha_hora)); ?>">
                        </div>
                    </div>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="departamento_id">Departamento:</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">business</i>
                            <select class="formulario__entrada" id="departamento_id" name="departamento_id">
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
                            <i class="material-icons">assignment</i>
                            <select class="formulario__entrada" id="modalidad_id" name="modalidad_id">
                                <?php foreach ($modalidades as $modalidad) { ?>
                                    <option value="<?php echo $modalidad->id; ?>" <?php echo $entrevista->modalidad_id == $modalidad->id ? 'selected' : ''; ?>>
                                        <?php echo $modalidad->nombre; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="formulario__seccion">
                    <legend class="formulario__leyenda">Curriculum Vitae</legend>
                    <div class="formulario__campo">
                        <label class="formulario__etiqueta" for="curriculum">Curriculum (PDF):</label>
                        <div class="formulario__input-con-icono">
                            <i class="material-icons">file_present</i>
                            <input class="formulario__entrada" type="file" id="curriculum" name="curriculum">
                        </div>
                    </div>
                </fieldset>

                <input class="formulario__boton" type="submit" value="Actualizar Cita">
            </div>
        </form>
    </div>
</div>
