<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__user-info">
    <h3><?php echo htmlspecialchars($user_nombre . ' ' . $user_apellido); ?></h3>
    <p><?php echo htmlspecialchars($user_puesto_trabajo); ?></p>
    <p><?php echo htmlspecialchars($user_departamento_nombre); ?></p>
    <p><?php echo ucfirst(htmlspecialchars($user_role)); ?></p>
</div>

<?php if ($user_role === 'admin') { ?>
    <div class="dashboard__contenedor-boton">
        <a class="dashboard__boton" href="/admin/empleados/crear">
            <i class="fa-solid fa-circle-plus"></i>
            Añadir Empleado
        </a>
    </div>
<?php } ?>

<div class="dashboard__contenedor">
    <?php if (!empty($empleados)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Imagen</th>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Apellido</th>
                    <th scope="col" class="table__th">Ciudad</th>
                    <th scope="col" class="table__th">País</th>
                    <th scope="col" class="table__th">Puesto de Trabajo</th>
                    <th scope="col" class="table__th">Departamento</th>
                    <?php if ($user_role === 'admin' || $user_role === 'jefe') { ?>
                        <th scope="col" class="table__th">Acciones</th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach ($empleados as $empleado) { ?>
                    <tr class="table__tr">
                        <td class="table__td" data-label="Imagen">
                            <img src="/img/galeria/<?php echo $empleado->imagen; ?>.png" alt="Imagen de <?php echo $empleado->nombre; ?>" class="imagen-tabla">
                        </td>
                        <td class="table__td" data-label="Nombre">
                            <?php echo $empleado->nombre; ?>
                        </td>
                        <td class="table__td" data-label="Apellido">
                            <?php echo $empleado->apellido; ?>
                        </td>
                        <td class="table__td" data-label="Ciudad">
                            <?php echo $empleado->ciudad; ?>
                        </td>
                        <td class="table__td" data-label="País">
                            <?php echo $empleado->pais; ?>
                        </td>
                        <td class="table__td" data-label="Puesto de Trabajo">
                            <?php echo $empleado->puesto_trabajo_nombre; ?>
                        </td>
                        <td class="table__td" data-label="Departamento">
                            <?php echo $empleado->departamento_nombre; ?>
                        </td>
                        <?php if ($user_role === 'admin' || $user_role === 'jefe') { ?>
                            <td class="table__td table__td--acciones" data-label="Acciones">
                                <div class="acciones-grid">
                                    <a class="table__accion table__accion--editar" href="/admin/empleados/editar?id=<?php echo $empleado->id; ?>">
                                        <i class="material-icons">edit</i>
                                        Editar
                                    </a>

                                    <form method="POST" action="/admin/empleados/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $empleado->id; ?>">
                                        <button class="table__accion table__accion--eliminar" type="submit">
                                            <i class="material-icons">delete</i>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No Hay Empleados Aún</p>
    <?php } ?>
</div>

<?php echo $paginacion; ?>
