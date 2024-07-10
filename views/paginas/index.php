<main class="container">
    <h2 class="agenda__heading">Departamentos</h2>
    <p class="agenda__descripcion">Conoce nuestras áreas de trabajo</p>
    <div class="swiper-container carrusel">
        <div class="swiper-wrapper">
            <?php foreach ($departamentos as $departamento) { ?>
                <div class="swiper-slide carrusel__slide">
                    <div class="carrusel__tarjeta">
                        <?php 
                        $imagen = !empty($departamento->imagen) ? htmlspecialchars($departamento->imagen) : 'default-image';
                        $encargado_imagen = !empty($departamento->encargado->imagen) ? htmlspecialchars($departamento->encargado->imagen) : 'default-avatar';
                        ?>
                        <img src="/img/galeria/<?php echo $imagen; ?>.png" alt="Imagen Departamento" class="carrusel__tarjeta__imagen">
                        <div class="carrusel__tarjeta__contenido">
                            <h2 class="carrusel__tarjeta__titulo"><?php echo htmlspecialchars($departamento->nombre_departamento); ?></h2>
                            <p class="carrusel__tarjeta__descripcion"><?php echo htmlspecialchars($departamento->descripcion); ?></p>
                            <div class="carrusel__tarjeta__pie">
                                <img src="/img/galeria/<?php echo $encargado_imagen; ?>.png" alt="Avatar" class="carrusel__tarjeta__avatar">
                                <span class="carrusel__tarjeta__nombre"><?php echo htmlspecialchars($departamento->encargado->nombre . ' ' . $departamento->encargado->apellido); ?></span>
                                <button href="/departamento?id=<?php echo $departamento->id; ?>" class="carrusel__tarjeta__boton"><i class="material-icons">arrow_forward</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<div class="separator"></div>

<section class="requisitos">
    <h2 class="requisitos__heading">Requisitos</h2>
    <div class="requisitos__contenedor">
        <div class="requisito">
            <i class="requisito__icono fas fa-clock"></i>
            <p class="requisito__texto">Disponibilidad de tiempo</p>
        </div>
        <div class="requisito">
            <i class="requisito__icono fas fa-graduation-cap"></i>
            <p class="requisito__texto">Estudiante de carrera a fin al área</p>
        </div>
        <div class="requisito">
            <i class="requisito__icono fas fa-book"></i>
            <p class="requisito__texto">No adeudos de materias</p>
        </div>
        <div class="requisito">
            <i class="requisito__icono fas fa-lightbulb"></i>
            <p class="requisito__texto">Conocimiento del área</p>
        </div>
        <div class="requisito">
            <i class="requisito__icono fas fa-file-alt"></i>
            <p class="requisito__texto">Traer CV impreso</p>
        </div>
    </div>
</section>

<div class="separator"></div>

<section>
    <h2 class="mapa__heading">Ubicación</h2>
    <div id="mapa" class="mapa"></div>
    <div class="mapa__info">
        <p class="mapa__info__direccion">Luis Carracci 146, Extremadura Insurgentes, Benito Juárez, 03740 Ciudad de México, CDMX</p>
        <div class="mapa__info__horarios">
            <div class="mapa__info__horarios__horario">
                <p class="mapa__info__horarios__horario__texto">Lunes a Viernes: 09:00 - 16:00</p>
            </div>
            <div class="mapa__info__horarios__horario">
                <p class="mapa__info__horarios__horario__texto">Sábado y Domingo: No Laboral</p>
            </div>
        </div>
    </div>
</section>


