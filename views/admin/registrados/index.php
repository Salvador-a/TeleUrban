<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


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
                        <td class="table__td table__td--acciones">
                            <div class="actions">
                                <?php if ($mostrarAcciones) { ?>
                                    <form method="POST" action="/admin/registrados/aceptar" class="table__formulario">
                                        <button class="table__accion table__accion--aceptar" type="submit" <?php echo $entrevista->estatus_id != 1 ? 'disabled' : ''; ?>>
                                            <i class="material-icons">check_circle</i>
                                            <span>Aceptar</span>
                                        </button>
                                        <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                    </form>
                                    <form method="POST" action="/admin/registrados/rechazar" class="table__formulario">
                                        <button class="table__accion table__accion--rechazar" type="submit" <?php echo $entrevista->estatus_id != 1 ? 'disabled' : ''; ?>>
                                            <i class="material-icons">cancel</i>
                                            <span>Rechazar</span>
                                        </button>
                                        <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                    </form>
                                <?php } ?>
                                <a class="table__accion table__accion--cv" href="/admin/registrados/cv?id=<?php echo $entrevista->id; ?>" target="_blank">
                                    <i class="material-icons">description</i>
                                    <span>CV</span>
                                </a>
                                <a class="table__accion table__accion--ver" href="/admin/registrados/ver?id=<?php echo $entrevista->id; ?>">
                                    <i class="material-icons">visibility</i>
                                    <span>Ver Más Información</span>
                                </a>
                                <form method="POST" action="/admin/registrados/eliminar" class="table__formulario">
                                    <button class="table__accion table__accion--eliminar" type="submit">
                                        <i class="material-icons">delete</i>
                                        <span>Eliminar</span>
                                    </button>
                                    <input type="hidden" name="id" value="<?php echo $entrevista->id; ?>">
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay citas de entrevistas registradas</p>
    <?php } ?>
</div>
