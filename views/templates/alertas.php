<?php 
    // Recorre cada alerta en el array de alertas
    foreach($alertas as $key => $alerta) {
        // Recorre cada mensaje en la alerta actual
        foreach($alerta as $mensaje) {
?>
            <div class="alerta alerta__<?php echo $key; ?>">
                <?php echo $mensaje; ?>
            </div>

<?php 
        }
    }
?>