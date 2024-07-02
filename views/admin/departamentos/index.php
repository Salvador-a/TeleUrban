<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__user-info">
    <h3><?php echo htmlspecialchars($user_nombre . ' ' . $user_apellido); ?></h3>
    <p><?php echo htmlspecialchars($user_puesto_trabajo); ?></p>
    <p><?php echo htmlspecialchars($user_departamento_nombre); ?></p>
    <p><?php echo ucfirst(htmlspecialchars($user_role)); ?></p>
</div>

<?php if ($user_role === 'admin') { ?>
    <div class="dashboard__contenedor-boton">
        <a class="dashboard__boton" href="/admin/departamentos/crear">
            <i class="fa-solid fa-circle-plus"></i>
            Añadir Departamentos
        </a>
    </div>
<?php } ?>

<div class="dashboard__contenedor">
    <?php if (!empty($departamentos)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Imagen</th>
                    <th scope="col" class="table__th">Departamento</th>
                    <th scope="col" class="table__th">Encargado</th>
                    <th scope="col" class="table__th">Descripción</th>
                    <th scope="col" class="table__th">Acciones</th>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach ($departamentos as $departamento) { ?>
                    <tr class="table__tr">
                        <td class="table__td" data-label="Imagen">
                            <?php if (!empty($departamento->imagen)) { ?>
                                <picture>
                                    <source srcset="/img/galeria/<?php echo $departamento->imagen; ?>.webp" type="image/webp">
                                    <source srcset="/img/galeria/<?php echo $departamento->imagen; ?>.png" type="image/png">
                                    <img src="/img/galeria/<?php echo $departamento->imagen; ?>.png" alt="Imagen de <?php echo $departamento->nombre_departamento; ?>" class="imagen-tabla">
                                </picture>
                            <?php } else { ?>
                                <img src="/img/default.png" alt="Imagen por defecto" class="imagen-tabla">
                            <?php } ?>
                        </td>
                        <td class="table__td" data-label="Departamento">
                            <?php echo htmlspecialchars($departamento->nombre_departamento); ?>
                        </td>
                        <td class="table__td" data-label="Encargado">
                            <div class="encargado">
                                <?php if (!empty($departamento->encargado_imagen)) { ?>
                                    <img src="/img/galeria/<?php echo $departamento->encargado_imagen; ?>.png" alt="Imagen de <?php echo htmlspecialchars($departamento->encargado_nombre); ?>" class="encargado__imagen">
                                <?php } else { ?>
                                    <img src="/img/default.png" alt="Imagen por defecto" class="encargado__imagen">
                                <?php } ?>
                                <div class="encargado__info">
                                    <p class="encargado__nombre"><?php echo htmlspecialchars($departamento->encargado_nombre) . " " . htmlspecialchars($departamento->encargado_apellido); ?></p>
                                    <p class="encargado__puesto"><?php echo htmlspecialchars($departamento->encargado_puesto); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="table__td table__td--descripcion" data-label="Descripción">
                            <div class="table__descripcion">
                                <?php echo htmlspecialchars($departamento->descripcion); ?>
                            </div>
                        </td>
                        <td class="table__td table__td--acciones" data-label="Acciones">
                            <div class="acciones-grid">
                                <a class="table__accion table__accion--editar" href="/admin/departamentos/editar?id=<?php echo $departamento->id; ?>">
                                    <i class="material-icons">edit</i>
                                    Editar
                                </a>

                                <form method="POST" action="/admin/departamentos/eliminar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                    <button class="table__accion table__accion--eliminar" type="submit">
                                        <i class="material-icons">delete</i>
                                        Eliminar
                                    </button>
                                </form>

                                <form method="POST" action="/admin/departamentos/publicar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                    <button class="table__accion table__accion--publicar" type="submit" <?php echo $departamento->publicado ? 'disabled' : ''; ?>>
                                        <i class="material-icons">check_circle</i>
                                        Publicar
                                    </button>
                                </form>

                                <form method="POST" action="/admin/departamentos/despublicar" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                    <button class="table__accion table__accion--despublicar" type="submit" <?php echo !$departamento->publicado ? 'disabled' : ''; ?>>
                                        <i class="material-icons">cancel</i>
                                        Despublicar
                                    </button>
                                </form>

                                <form method="POST" action="/admin/departamentos/toggle-disponibilidad" class="table__formulario">
                                    <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                    <button class="table__accion table__accion--toggle" type="submit">
                                        <i class="material-icons"><?php echo $departamento->disponible ? 'toggle_on' : 'toggle_off'; ?></i>
                                        <?php echo $departamento->disponible ? 'Desactivar' : 'Activar'; ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No Hay Departamentos Aún</p>
    <?php } ?>
</div>

<?php echo $paginacion; ?>
