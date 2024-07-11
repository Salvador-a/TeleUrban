<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Define el título de la página -->
    <title>TeleUrban | <?php echo $titulo; ?></title>
    
    <!-- Establece una preconexión con Google Fonts para mejorar el rendimiento de carga -->
    <link rel="preconnect" href="https://fonts.googleapis.com"> 
    <!-- Establece una preconexión con el servidor de fuentes estáticas de Google para mejorar el rendimiento de carga -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <!-- Importa la fuente Outfit de Google Fonts con diferentes pesos de fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet"> 
    <!-- Importa los estilos de la biblioteca Leaflet para la creación de mapas interactivos -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" /> 
    <!-- Importa los estilos de la biblioteca Font Awesome para usar iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <!-- Importa los estilos personalizados de la aplicación -->
    <link rel="stylesheet" href="/build/css/app.css"> 
    <!-- importa los estilos de swiper -->
     <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <!-- Importa los estilos de la biblioteca AOS para animaciones al hacer scroll -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"> 
    <!-- Importa la biblioteca Leaflet para la creación de mapas interactivos -->
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin="" defer></script> 
    <!-- Importacion de los iconos de google -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- FancyBox CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />

<!-- FancyBox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <?php 
        include_once __DIR__ .'/templates/header.php'; // Incluye el archivo de la cabecera
        echo $contenido; // Muestra el contenido de la página
        include_once __DIR__ .'/templates/footer.php'; // Incluye el archivo del pie de página
    ?>
    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script> <!-- Importa la biblioteca AOS para animaciones al hacer scroll -->
    <script>
        AOS.init(); // Inicializa la biblioteca AOS
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script src="/build/js/main.min.js" defer></script> <!-- Importa el archivo JavaScript principal de la aplicación -->
</body>
</html>