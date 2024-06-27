<div class="ver">
    <div class="ver__card">
        <div class="ver__card__header">
            <h2 class="card__name"><?php echo $entrevista->nombre . ' ' . $entrevista->a_paterno . ' ' . $entrevista->a_materno; ?></h2>
            <p class="card__email"><i class="fas fa-envelope"></i> <?php echo $entrevista->email; ?></p>
            <p class="card__phone"><i class="fas fa-phone"></i> <?php echo $entrevista->telefono; ?></p>
        </div>
        <div class="ver__card__body">
            <div class="card__section">
                <h3>Detalles Personales</h3>
                <p><i class="fas fa-user"></i> <strong>Nombre:</strong> <?php echo $entrevista->nombre; ?></p>
                <p><i class="fas fa-user"></i> <strong>Apellido Paterno:</strong> <?php echo $entrevista->a_paterno; ?></p>
                <p><i class="fas fa-user"></i> <strong>Apellido Materno:</strong> <?php echo $entrevista->a_materno; ?></p>
                <p><i class="fas fa-male"></i> <strong>Género:</strong> <?php echo $entrevista->genero_nombre; ?></p>
                <p><i class="fas fa-wheelchair"></i> <strong>Discapacidad:</strong> <?php echo $entrevista->discapacidad_nombre; ?></p>
            </div>
            <div class="card__section">
                <h3>Detalles Educativos</h3>
                <p><i class="fas fa-university"></i> <strong>Universidad:</strong> <?php echo $entrevista->universidad_nombre; ?></p>
                <p><i class="fas fa-graduation-cap"></i> <strong>Semestre:</strong> <?php echo $entrevista->semestre_nombre; ?></p>
                <p><i class="fas fa-book"></i> <strong>Modalidad:</strong> <?php echo $entrevista->modalidad_nombre; ?></p>
            </div>
            <div class="card__section">
                <h3>Detalles Profesionales</h3>
                <p><i class="fas fa-building"></i> <strong>Departamento:</strong> <?php echo $entrevista->departamento_nombre; ?></p>
                <p><i class="fas fa-clock"></i> <strong>Fecha y Hora:</strong> <?php echo $entrevista->fecha_hora; ?></p>
                <p><i class="fas fa-file"></i> <strong>Curriculum:</strong> <a href="/cv/<?php echo $entrevista->curriculum; ?>" target="_blank">Ver Curriculum</a></p>
            </div>
            <div class="card__section card__skills">
                <h3>Habilidades</h3>
                <p><i class="fas fa-tasks"></i> <?php echo $entrevista->habilidades; ?></p>
            </div>
        </div>
    </div>
</div>
