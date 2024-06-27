<!-- admin/registrados/aceptados.php -->
<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/registrados/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Cita de Entrevista
    </a>
</div>

<?php 
$mostrarAcciones = false;
include __DIR__ . '/index.php'; 
?>
