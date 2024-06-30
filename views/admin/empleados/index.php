<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <?php if($user_role === 'admin' || $user_role === 'jefe') { ?>
        <a class="dashboard__boton" href="/admin/empleados/crear">
            <i class="fa-solid fa-circle-plus"></i>
            Añadir Empleado
        </a>
    <?php } ?>
</div>

<div class="dashboard__contenedor">
    <?php if (!empty($empleados)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Imagen</th>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Puesto de Trabajo</th>
                    <th scope="col" class="table__th">Departamento</th>
                    <th scope="col" class="table__th">Áreas de Experiencia</th>
                    <th scope="col" class="table__th">Acciones</th>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach($empleados as $empleado) { ?>
                    <tr class="table__tr">
                        <td class="table__td" data-label="Imagen">
                            <img src="/img/galeria/<?php echo $empleado->imagen; ?>.png" alt="Imagen de <?php echo $empleado->nombre; ?>" class="imagen-tabla">
                        </td>
                        <td class="table__td" data-label="Nombre">
                            <?php echo $empleado->nombre . " " . $empleado->apellido; ?>
                        </td>
                        <td class="table__td" data-label="Puesto de Trabajo">
                            <?php echo $empleado->puesto_trabajo_nombre; ?>
                        </td>
                        <td class="table__td" data-label="Departamento">
                            <?php echo $empleado->departamento_nombre; ?>
                        </td>
                        <td class="table__td" data-label="Áreas de Experiencia">
                            <?php echo $empleado->tags; ?>
                        </td>
                        <td class="table__td table__td--acciones" data-label="Acciones">
                            <?php if($user_role === 'admin' || $user_role === 'jefe') { ?>
                                <a class="table__accion table__accion--editar" href="/admin/empleados/editar?id=<?php echo $empleado->id; ?>">
                                    <i class="fa-solid fa-user-pen"></i>
                                    Editar
                                </a>

                                <form method="POST" action="/admin/empleados/eliminar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $empleado->id; ?>">
                                    <button class="table__accion table__accion--eliminar" type="submit">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        Eliminar
                                    </button>
                                </form>
                            <?php } else { ?>
                                <p>No tienes permisos para editar o eliminar empleados.</p>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No Hay Empleados Aún</p>
    <?php } ?>
</div>

<?php echo $paginacion; ?>
