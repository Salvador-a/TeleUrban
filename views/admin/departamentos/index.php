<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/departamentos/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Departamentos
    </a>
</div>

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
                            <img src="/img/galeria/<?php echo $departamento->imagen; ?>.png" alt="Imagen de <?php echo $departamento->nombre_departamento; ?>" class="imagen-tabla">
                        </td>
                        <td class="table__td" data-label="Departamento">
                            <?php echo $departamento->nombre_departamento; ?>
                        </td>
                        <td class="table__td" data-label="Encargado">
                            <div class="encargado">
                                <img src="/img/galeria/<?php echo $departamento->encargado_imagen; ?>.png" alt="Imagen de <?php echo $departamento->encargado_nombre; ?>" class="encargado__imagen">
                                <div class="encargado__info">
                                    <p class="encargado__nombre"><?php echo $departamento->encargado_nombre . " " . $departamento->encargado_apellido; ?></p>
                                    <p class="encargado__puesto"><?php echo $departamento->encargado_puesto; ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="table__td table__td--descripcion" data-label="Descripción">
                            <div class="table__descripcion">
                                <?php echo $departamento->descripcion; ?>
                            </div>
                        </td>
                        <td class="table__td table__td--acciones" data-label="Acciones">
                            <a class="table__accion table__accion--editar" href="/admin/departamentos/editar?id=<?php echo $departamento->id; ?>">
                                <i class="fa-solid fa-user-pen"></i>
                                Editar
                            </a>

                            <form method="POST" action="/admin/departamentos/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                <button class="table__accion table__accion--eliminar" type="submit">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Eliminar
                                </button>
                            </form>

                            <form method="POST" action="/admin/departamentos/publicar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $departamento->id; ?>">
                                <button class="table__accion table__accion--publicar" type="submit">
                                    <i class="fa-solid fa-circle-check"></i>
                                    Publicar
                                </button>
                            </form>
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
