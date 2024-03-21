<?php
// Conexión a la base de datos
$db = mysqli_connect(
    $_ENV['DB_HOST'] ?? '', // Host de la base de datos
    $_ENV['DB_USER'] ?? '', // Usuario de la base de datos
    $_ENV['DB_PASS'] ?? '', // Contraseña de la base de datos
    $_ENV['DB_NAME'] ?? ''  // Nombre de la base de datos
);

// Verifica si la conexión fue exitosa
if (!$db) {
    // Si la conexión falla, muestra un mensaje de error y termina la ejecución del script
    echo "Error: No se pudo conectar a MySQL.";
    echo "errno de depuración: " . mysqli_connect_errno(); // Número de error
    echo "error de depuración: " . mysqli_connect_error(); // Mensaje de error
    exit;
}