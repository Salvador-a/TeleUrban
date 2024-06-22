<main class="detalle__container">
    <h1 class="detalle__titulo-pagina"><?php echo $departamento->nombre_departamento; ?></h1>
    <div class="detalle__tarjeta">
        <div class="detalle__perfil">
            <img src="/img/galeria/<?php echo $departamento->encargado->imagen; ?>.png" alt="Imagen de perfil" class="detalle__perfil-imagen">
            <p class="detalle__perfil-nombre"><?php echo $departamento->encargado->nombre . ' ' . $departamento->encargado->apellido; ?></p>
            <p class="detalle__perfil-cargo"><?php echo $departamento->encargado->puesto_trabajo; ?></p>
        </div>
        <div class="detalle__contenido">
            <div class="detalle__contenido-mascara">
                <h1 class="detalle__titulo"><?php echo $departamento->nombre_departamento; ?></h1>
                <p class="detalle__subtitulo">
                    <?php echo nl2br($departamento->descripcion); ?>
                </p>
            </div>
        </div>
    </div>
</main>
