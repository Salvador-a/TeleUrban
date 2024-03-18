<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Define la codificación de caracteres para la página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Hace que la página sea responsive -->
    <title>DevWebCamp - <?php echo $titulo; ?></title> <!-- Establece el título de la página -->
    <link rel="preconnect" href="https://fonts.googleapis.com"> <!-- Preconecta a Google Fonts para una carga más rápida de las fuentes -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> <!-- Preconecta a Google Static Fonts para una carga más rápida de las fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet"> <!-- Importa la fuente Outfit de Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- Importa Font Awesome para usar iconos -->
    <link rel="stylesheet" href="/build/css/app.css"> <!-- Importa el archivo CSS principal de la aplicación -->
</head>
<body class="dashboard">
    <?php 
        // Incluye el archivo admin-header.php
        include_once __DIR__ .'/templates/admin-header.php';
    ?>
    <div class="dashboard__grid">
        <?php
            // Incluye el archivo admin-sidebar.php
            include_once __DIR__ .'/templates/admin-sidebar.php';  
        ?>

        <main class="dashboard__contenido">
            <?php 
                // Imprime el contenido de la variable $contenido
                echo $contenido; 
            ?> 
        </main>
    </div>

    <!-- Importa la biblioteca Chart.js desde un CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Importa la biblioteca Chart.js desde otro CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Importa el archivo JavaScript principal de la aplicación -->
    <script src="/build/js/main.min.js" defer></script>
</body>
</html>