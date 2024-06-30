<main class="detalle__container">
    <h1 class="detalle__titulo-pagina"><?php echo htmlspecialchars($departamento->nombre_departamento); ?></h1>
    <div class="detalle__tarjeta">
        <div class="detalle__perfil">
            <?php $encargado_imagen = $departamento->encargado->imagen ?? 'default-avatar'; ?>
            <img src="/img/galeria/<?php echo htmlspecialchars($encargado_imagen); ?>.png" alt="Imagen de perfil" class="detalle__perfil-imagen">
            <p class="detalle__perfil-nombre"><?php echo htmlspecialchars($departamento->encargado->nombre . ' ' . $departamento->encargado->apellido); ?></p>
            <p class="detalle__perfil-cargo"><?php echo htmlspecialchars($departamento->encargado->puesto_trabajo ?? ''); ?></p>
        </div>
        <div class="detalle__contenido">
            <div class="detalle__contenido-mascara">
                <h1 class="detalle__titulo"><?php echo htmlspecialchars($departamento->nombre_departamento); ?></h1>
                <p class="detalle__subtitulo">
                    <?php echo nl2br(htmlspecialchars($departamento->descripcion)); ?>
                </p>
            </div>
        </div>
    </div>
</main>
