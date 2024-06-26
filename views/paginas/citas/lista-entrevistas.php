<!-- paginas/citas/lista-entrevistas.php -->
<div class="lista-entrevistas">
    <h1>Lista de Entrevistas</h1>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Discapacidad</th>
                <th>Género</th>
                <th>Semestre</th>
                <th>Universidad</th>
                <th>Departamento</th>
                <th>Modalidad</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entrevistas as $entrevista) { ?>
                <tr>
                    <td><?php echo $entrevista->nombre; ?></td>
                    <td><?php echo $entrevista->a_paterno; ?></td>
                    <td><?php echo $entrevista->a_materno; ?></td>
                    <td><?php echo $entrevista->email; ?></td>
                    <td><?php echo $entrevista->telefono; ?></td>
                    <td><?php echo $entrevista->discapacidad_nombre; ?></td>
                    <td><?php echo $entrevista->genero_nombre; ?></td>
                    <td><?php echo $entrevista->semestre_grado; ?></td>
                    <td><?php echo $entrevista->universidad_nombre; ?></td>
                    <td><?php echo $entrevista->departamento_nombre; ?></td>
                    <td><?php echo $entrevista->modalidad_nombre; ?></td>
                    <td><?php echo $entrevista->fecha_hora; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
