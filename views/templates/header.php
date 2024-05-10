<header class="header">
    <div class="header__contenedor">

        <nav class="header__navegacion">

            <?php if (is_auth()) { ?>
                <a href="<?php echo is_admin() ? '/admin/dashboard' : '/finalizar-registro'; ?>" class="header__enlace">Administrar</a>
                <form method="POST" action="/logout" class="header__form">
                    <input type="submit" value="Cerrar Sesión" class="header__submit">
                </form>
            <?php } else { ?>
                <a href="/registro" class="header__enlace">Registro</a>
                <a href="/login" class="header__enlace">Iniciar Sesión</a>
            <?php } ?>
        </nav>
        <div class="header__contenido">
            <a href="/">
                <h1 class="header__logo">
                    TeleUrban
                </h1>
            </a>

            <p class="header__texto">SOBRE NOSOTROS</p>
            <p class="header__texto header__texto--modalidad">
                Es un sistema en el transporte público que <br>
                ofrece información y entretenimiento a través de <br>
                pantallas y altavoces en diferentes partes de México.
            </p>

            
        </div>

    </div>
</header>
<div class="barra">
    <div class="barra__contenido">
        <a href="/">
            <h2 class="barra__logo">
                TeleUrban
            </h2>
        </a>
        <nav class="navegacion">
            <a href="/devwebcamp" class="navegacion__enlace <?php echo pagina_actual('/devwebcamp') ? 'navegacion__enlace--actual' : ''; ?>">Nosotros</a>
            <a href="/paquetes" class="navegacion__enlace <?php echo pagina_actual('/paquetes') ? 'navegacion__enlace--actual' : ''; ?>">Áreas de trabajo</a>
            <a href="/workshops-coferencias" class="navegacion__enlace <?php echo pagina_actual('/workshops-coferencias') ? 'navegacion__enlace--actual' : ''; ?>">Requisitos</a>
            <a href="/registro" class="navegacion__enlace <?php echo pagina_actual('/registro') ? 'navegacion__enlace--actual' : ''; ?>">Agenda cita</a>

        </nav>
    </div>
</div>