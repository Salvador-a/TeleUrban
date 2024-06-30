<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Regístrate</p>

    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

    <form method="POST" action="/registro" class="formulario">

        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Nombre"
                id="nombre"
                name="nombre"
                value="<?php echo s($usuario->nombre); ?>"
            />
        </div> 

        <div class="formulario__campo">
            <label class="formulario__label" for="a_paterno">Apellido Paterno</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Apellido Paterno"
                id="a_paterno"
                name="a_paterno"
                value="<?php echo s($usuario->a_paterno); ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="a_materno">Apellido Materno</label>
            <input 
                type="text"
                class="formulario__input"
                placeholder="Tu Apellido Materno"
                id="a_materno"
                name="a_materno"
                value="<?php echo s($usuario->a_materno); ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input 
                type="email"
                class="formulario__input"
                placeholder="Tu Email"
                id="email"
                name="email"
                value="<?php echo s($usuario->email); ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password">Password</label>
            <input 
                type="password"
                class="formulario__input"
                placeholder="Tu Password"
                id="password"
                name="password"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password2">Repite tu Password</label>
            <input 
                type="password"
                class="formulario__input"
                placeholder="Repite tu Password"
                id="password2"
                name="password2"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="telefono">Teléfono</label>
            <input 
                type="tel"
                class="formulario__input"
                placeholder="Tu Teléfono"
                id="telefono"
                name="telefono"
                value="<?php echo s($usuario->telefono); ?>"
            />
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="genero_id">Género</label>
            <select class="formulario__input" id="genero_id" name="genero_id">
                <option value="">-- Seleccionar --</option>
                <?php foreach($generos as $genero): ?>
                    <option value="<?php echo $genero->id; ?>" <?php echo $usuario->genero_id == $genero->id ? 'selected' : ''; ?>>
                        <?php echo $genero->nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="departamento_id">Departamento</label>
            <select class="formulario__input" id="departamento_id" name="departamento_id">
                <option value="">-- Seleccionar --</option>
                <?php foreach($departamentos as $departamento): ?>
                    <option value="<?php echo $departamento->id; ?>" <?php echo $usuario->departamento_id == $departamento->id ? 'selected' : ''; ?>>
                        <?php echo $departamento->nombre_departamento; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="puesto_trabajo_id">Puesto de Trabajo</label>
            <select class="formulario__input" id="puesto_trabajo_id" name="puesto_trabajo_id">
                <option value="">-- Seleccionar --</option>
                <?php foreach($puestos_trabajo as $puesto): ?>
                    <option value="<?php echo $puesto->id; ?>" <?php echo $usuario->puesto_trabajo_id == $puesto->id ? 'selected' : ''; ?>>
                        <?php echo $puesto->nombre_puesto; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <input type="submit" class="formulario__submit" value="Crear Cuenta">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Iniciar Sesión</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidaste tu password?</a>
    </div>
</main>
