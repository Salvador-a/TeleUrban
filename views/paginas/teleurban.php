<main class="teleurban">
    <h2 class="teleurban__heading"><?php echo $titulo; ?></h2>
    <p class="teleurban__descripcion">Transformando la experiencia del transporte público con tecnología avanzada</p>

    <div class="teleurban__grid">
        <div <?php aos_animacion(); ?> class="teleurban__imagen">
            <picture>
                <source srcset="build/imagenes/sobre_teleurban.avif" type="image/avif">
                <source srcset="build/img/sobre_teleurban.webp" type="image/webp">
                <img loading="lazy" width="200" height="300" src="build/img/sobre_teleurban.jpg" alt="Imagen teleurban">
            </picture>
        </div>

        <div  class="teleurban__contenido">
            <p <?php aos_animacion(); ?> class="teleurban__texto">TELE URBAN ha revolucionado la comunicación en el transporte público durante 23 años. Con monitores de alta resolución y bocinas de alta fidelidad, brindamos información actualizada y entretenimiento de alta calidad a millones de usuarios.</p>
            
            <p <?php aos_animacion(); ?> class="teleurban__texto">Nuestra tecnología está presente en los principales sistemas de transporte urbano, incluyendo el METROBÚS de la Ciudad de México, MEXIBÚS del Estado de México, Tren Suburbano, METROBÚS de Puebla (RUTA), MACROBÚS de Guadalajara, Turicún en Cancún y TUZOBÚS en Pachuca.</p>
        </div>
    </div>
</main>