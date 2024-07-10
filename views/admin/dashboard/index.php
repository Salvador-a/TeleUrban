<main class="bloques">
    <div class="bloques__grid">
        <div class="bloque">
            <h3 class="bloque__heading">Postulantes por Área</h3>
            <canvas id="postulantesPorArea" data-postulantes-por-area='<?php echo json_encode($postulantesPorArea); ?>'></canvas>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Citas por Fecha</h3>
            <canvas id="citasPorFecha" data-citas-por-fecha='<?php echo json_encode($citasPorFecha); ?>'></canvas>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Estado de las Entrevistas</h3>
            <canvas id="estadoEntrevistas" data-estado-entrevistas='<?php echo json_encode($estadoEntrevistas); ?>'></canvas>
        </div>
    </div>
</main>
