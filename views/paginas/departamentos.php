<main class="agenda">
    <h2 class="agenda__heading">Departamentos</h2>
    <p class="agenda__descripcion">Conoce nuestras áreas de trabajo</p>

    <section id="areas-de-trabajo" class="contenedor categorias">
        <div class="categorias__card-container">
            <?php if (empty($departamentos)) { ?>
                <p>No hay departamentos disponibles en este momento.</p>
            <?php } else { ?>
                <?php foreach ($departamentos as $departamento) { ?>
                    <?php if ($departamento->publicado == 1) { ?>
                        <div class="categorias__card">
                            <div class="categorias__card-header">
                                <?php 
                                $imagen = !empty($departamento->imagen) ? htmlspecialchars($departamento->imagen) : 'default-image';
                                $encargado_imagen = !empty($departamento->encargado->imagen) ? htmlspecialchars($departamento->encargado->imagen) : 'default-avatar';
                                ?>
                                <picture>
                                    <source class="categorias__background-image" srcset="/img/galeria/<?php echo $imagen; ?>.webp" type="image/webp">
                                    <source class="categorias__background-image" srcset="/img/galeria/<?php echo $imagen; ?>.png" type="image/png">
                                    <img class="categorias__background-image" loading="lazy" width="200" height="300" src="/img/galeria/<?php echo $imagen; ?>.png" alt="Imagen TeleUrban">
                                </picture>

                                <picture>
                                    <img class="categorias__avatar" loading="lazy" width="200" height="300" src="/img/galeria/<?php echo $encargado_imagen; ?>.png" alt="Imagen del Encargado">
                                </picture>
                            </div>
                            <div class="categorias__card-body">
                                <h2><?php echo htmlspecialchars($departamento->nombre_departamento); ?></h2>
                                <p class="categorias__description"><?php echo htmlspecialchars($departamento->descripcion); ?></p>
                            </div>
                            <div class="categorias__card-footer">
                                <a href="/departamento?id=<?php echo $departamento->id; ?>" class="categorias__follow-btn">Más</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    </section>
</main>
