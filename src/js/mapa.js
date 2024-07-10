if (document.querySelector("#mapa")) {

    const lat =  19.432264
    const lng = -98.983563
    const zoom = 20
    
      
    const map = L.map("mapa").setView([lat, lng], zoom);
  
    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);
  
    L.marker([lat, lng])
      .addTo(map)
      .bindPopup(`
          <h2 class="mapa__heading">TeleUrban</h2>
          <p class="mapa__texto">Oficinas</p>
          
      `)
      .openPopup();
  }
  