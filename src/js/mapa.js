if (document.querySelector("#mapa")) {

  const lat = 19.37543402120935;
  const lng = -99.17962636053633;
  const zoom = 18; // Ajustamos el zoom para que el popup se vea mejor

  const map = L.map("mapa").setView([lat, lng], zoom);

  L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  // Función para crear el contenido del popup
  const createPopupContent = (imagePath) => `
    <div class="mapa__popup">
      <h2 class="mapa__heading">TeleUrban</h2>
      <a href="${imagePath}" data-fancybox="gallery" data-caption="TeleUrban Oficinas">
        <img src="${imagePath}" alt="TeleUrban Oficinas" class="mapa__imagen">
      </a>
      <a href="/teleurban" class="mapa__boton">Más información</a>
    </div>
  `;

  // Lista de rutas de imagen en orden de preferencia
  const imageFormats = [
    "build/imagenes/TeleUrbanUbicacion.avif",
    "build/imagenes/TeleUrbanUbicacion.webp",
    "build/imagenes/TeleUrbanUbicacion.png"
  ];

  // Selección de la primera imagen disponible
  const selectImage = (formats) => {
    for (const format of formats) {
      const img = new Image();
      img.src = format;
      if (img.complete || img.width > 0) {
        console.log(`Image loaded: ${format}`);
        return format;
      } else {
        console.log(`Failed to load image: ${format}`);
      }
    }
    return formats[formats.length - 1]; // Fallback a la última imagen si ninguna está cargada
  };

  const selectedImage = selectImage(imageFormats);

  console.log(`Selected image: ${selectedImage}`);

  const marker = L.marker([lat, lng]).addTo(map);
  marker.bindPopup(createPopupContent(selectedImage)).openPopup();

  // Centrar el mapa con el popup abierto
  map.on('popupopen', function (e) {
    const px = map.project(e.popup._latlng);
    px.y -= e.popup._container.clientHeight / 2;
    map.panTo(map.unproject(px), { animate: true });
  });

  // Forzar la apertura del popup en el centro del mapa al cargar la página
  map.on('load', function () {
    marker.openPopup();
    const px = map.project(marker.getLatLng());
    px.y -= marker.getPopup()._container.clientHeight / 2;
    map.panTo(map.unproject(px), { animate: true });
  });

  // Disparar el evento 'load' manualmente
  map.fire('load');
}
