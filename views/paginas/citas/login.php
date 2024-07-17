<main class="auth">
    <h2 class="auth__heading">Acceder a Edición de Cita</h2>
    <p class="auth__texto">Introduce tu token para editar la cita</p>

    <?php require_once __DIR__ . '/../../templates/alertas.php'; ?>

    <form method="POST"  action="/login-cita" class="formulario">
        <div class="formulario__campo">
            <label class="formulario__label" for="token">Token</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Token"
                id="token"
                name="token"
                value="<?php echo isset($entrevista) ? htmlspecialchars($entrevista->token) : ''; ?>"
            />
        </div>

        <input type="submit" class="formulario__submit" value="Iniciar Sesión" />
    </form>
</main>
