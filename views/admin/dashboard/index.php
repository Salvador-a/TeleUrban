<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<main class="bloques">
    <div class="bloques__grid">
        <div class="bloque">
            <h3 class="bloque__heading">Últimos Registros</h3>
            <!-- Contenido dinámico según rol -->
            <?php if($user_role === 'admin') { ?>
                <!-- Contenido para admin -->
            <?php } elseif($user_role === 'jefe') { ?>
                <!-- Contenido para jefe -->
            <?php } elseif($user_role === 'trabajador') { ?>
                <!-- Contenido para trabajador -->
            <?php } ?>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Ingresos</h3>
            <!-- Contenido dinámico según rol -->
            <?php if($user_role === 'admin') { ?>
                <!-- Contenido para admin -->
            <?php } elseif($user_role === 'jefe') { ?>
                <!-- Contenido para jefe -->
            <?php } elseif($user_role === 'trabajador') { ?>
                <!-- Contenido para trabajador -->
            <?php } ?>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Eventos Con Menos Lugares Disponibles</h3>
            <!-- Contenido dinámico según rol -->
            <?php if($user_role === 'admin') { ?>
                <!-- Contenido para admin -->
            <?php } elseif($user_role === 'jefe') { ?>
                <!-- Contenido para jefe -->
            <?php } elseif($user_role === 'trabajador') { ?>
                <!-- Contenido para trabajador -->
            <?php } ?>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Eventos Con Más Lugares Disponibles</h3>
            <!-- Contenido dinámico según rol -->
            <?php if($user_role === 'admin') { ?>
                <!-- Contenido para admin -->
            <?php } elseif($user_role === 'jefe') { ?>
                <!-- Contenido para jefe -->
            <?php } elseif($user_role === 'trabajador') { ?>
                <!-- Contenido para trabajador -->
            <?php } ?>
        </div>
    </div>
</main>
