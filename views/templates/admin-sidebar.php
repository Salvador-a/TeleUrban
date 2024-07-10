<aside class="dashboard__sidebar">
    <nav class="dashboard__menu">
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">
                Inicio
            </span>
        </a>

        <a href="/admin/empleados" class="dashboard__enlace <?php echo pagina_actual('/empleados') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-user-tie"></i>

            <span class="dashboard__menu-texto">
                Empleados
            </span>
        </a>

        <a href="/admin/departamentos" class="dashboard__enlace  <?php echo pagina_actual('/departamentos') ? 'dashboard__enlace--actual' : ''; ?> ">
        <i class="fa-solid fa-building"></i>

            <span class="dashboard__menu-texto">
                Departamento 
            </span>
        </a>

        <a href="/admin/registrados" class="dashboard__enlace  <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual' : ''; ?> ">
            <i class="fa-solid fa-users dashboard__icono"></i>

            <span class="dashboard__menu-texto">
                Registrados
            </span>
        </a>

    </nav>

</aside>