<!-- views/paginas/citas/login.php -->
<div class="formulario-contenedor">
    <div class="contenedor-formulario">
        <h1 class="contenedor-formulario__titulo">Login para Editar Cita</h1>
        <form class="formulario" method="POST" action="/login-cita">
            <?php if (!empty($alertas)): ?>
                <div class="alertas">
                    <?php foreach ($alertas as $tipo => $mensajes): ?>
                        <?php foreach ($mensajes as $mensaje): ?>
                            <p class="alerta <?php echo $tipo; ?>"><?php echo $mensaje; ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="formulario__campo">
                <label class="formulario__etiqueta" for="token">Token:</label>
                <div class="formulario__input-con-icono">
                    <i class="material-icons">vpn_key</i>
                    <input class="formulario__entrada" type="text" id="token" name="token" placeholder="Ingresa tu token">
                </div>
            </div>
            <input class="formulario__boton" type="submit" value="Ingresar">
        </form>
    </div>
</div>
