<!-- admin/registrados/index.php -->
<?php
$mostrarAcciones = $mostrarAcciones ?? true;
?>

<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/registrados/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Cita de Entrevista
    </a>
</div>

<div class="filtros-estado">
    <button class="dashboard__filtro" data-filter="todos">
        <i class="fa-solid fa-list"></i>
        Todos
    </button>
    <button class="dashboard__filtro" data-filter="pendiente">
        <i class="fa-solid fa-hourglass-half"></i>
        Pendiente
    </button>
    <button class="dashboard__filtro" data-filter="aceptado">
        <i class="fa-solid fa-check"></i>
        Aceptado
    </button>
    <button class="dashboard__filtro" data-filter="rechazado">
        <i class="fa-solid fa-times"></i>
        Rechazado
    </button>
</div>

<div class="dashboard__contenedor">
    <?php if (!empty($entrevistas)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">E-mail</th>
                    <th scope="col" class="table__th">Teléfono</th>
                    <th scope="col" class="table__th">Semestre</th>
                    <th scope="col" class="table__th">Universidad</th>
                    <th scope="col" class="table__th">Fecha y hora</th>
                    <th scope="col" class="table__th">Descripción</th>
                    <th scope="col" class="table__th">Estatus</th>
                    <th scope="col" class="table__th">Acciones</th>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach ($entrevistas as $entrevista) { ?>
                    <tr class="table__tr" data-status="<?php echo strtolower($entrevista->estatus_nombre); ?>">
                        <td class="table__td"><?php echo $entrevista->nombre; ?></td>
                        <td class="table__td"><?php echo $entrevista->email; ?></td>
                        <td class="table__td"><?php echo $entrevista->telefono; ?></td>
                        <td class="table__td"><?php echo $entrevista->semestre_nombre; ?></td>
                        <td class="table__td"><?php echo $entrevista->universidad_nombre; ?></td>
                        <td class="table__td"><?php echo $entrevista->fecha_hora; ?></td>
                        <td class="table__td"><?php echo $entrevista->habilidades; ?></td>
                        <td class="table__td"><?php echo $entrevista->estatus_nombre; ?></td>
                        <td class="table__td--acciones">
                            <?php if ($mostrarAcciones) { ?>
                                <form method="POST" action="/admin/registrados/aceptar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                    <button class="table__accion table__accion--aceptar" type="submit" <?php echo $entrevista->estatus_id != 1 ? 'disabled' : ''; ?>>
                                        <i class="fa-solid fa-check"></i>
                                        Aceptar
                                    </button>
                                </form>
                                <form method="POST" action="/admin/registrados/rechazar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                    <button class="table__accion table__accion--rechazar" type="submit" <?php echo $entrevista->estatus_id != 1 ? 'disabled' : ''; ?>>
                                        <i class="fa-solid fa-times"></i>
                                        Rechazar
                                    </button>
                                </form>
                            <?php } ?>
                            <a class="table__accion table__accion--cv" href="/admin/registrados/cv?id=<?php echo $entrevista->id; ?>" target="_blank">
                                <i class="fa-solid fa-file"></i>
                                CV
                            </a>
                            <a class="table__accion table__accion--ver" href="/admin/registrados/ver?id=<?php echo $entrevista->id; ?>">
                                <i class="fa-solid fa-eye"></i>
                                Ver Más Información
                            </a>
                            <form method="POST" action="/admin/registrados/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                <button class="table__accion table__accion--eliminar" type="submit">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay citas de entrevistas registradas</p>
    <?php } ?>
</div>
