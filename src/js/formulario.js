document.addEventListener('DOMContentLoaded', function () {
    function mostrarPagina(pagina) {
        var paginas = document.querySelectorAll('.formulario__pagina');
        
        paginas.forEach(function (paginaElemento) {
            paginaElemento.classList.remove('formulario__pagina--activa');
        });
        
        var paginaActiva = document.getElementById('pagina' + pagina);
        if (paginaActiva) {
            paginaActiva.classList.add('formulario__pagina--activa');
        }
    }

    window.mostrarPagina = mostrarPagina;

    flatpickr('#fecha_hora', {
        locale: 'es',
        enableTime: true,
        dateFormat: "Y-m-d h:i K", // Formato de 12 horas con AM/PM
        minDate: "today",
        time_24hr: false,
        minTime: "10:00",
        maxTime: "16:00",
        minuteIncrement: 60,
        disable: [
            function(date) {
                const today = new Date();
                if (date < today) {
                    return true;
                } else if (date.getDay() === 0 || date.getDay() === 6) {
                    return true;
                } else if (date.toDateString() === today.toDateString() && date.getHours() >= 16) {
                    return true;
                }
                return false;
            }
        ],
        onClose: function(selectedDates, dateStr, instance) {
            let selectedDate = selectedDates[0];
            if (selectedDate) {
                let hours = selectedDate.getHours();
                let minutes = selectedDate.getMinutes();
                
                // Ajustar minutos a 0 si no es una hora exacta
                if (minutes !== 0) {
                    selectedDate.setMinutes(0);
                }
                
                // Ajustar horas fuera del rango permitido
                if (hours < 10) {
                    selectedDate.setHours(10);
                } else if (hours >= 16) {
                    selectedDate.setHours(16);
                }

                instance.setDate(selectedDate, true);

                // Validar la fecha y hora seleccionadas
                validarFechaHora(dateStr);
            }
        }
    });

    function validarFechaHora(fechaHora) {
        const areaId = document.querySelector('#area_id').value;
        fetch('/validar-fecha-hora', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ fecha_hora: fechaHora, area_id: areaId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.mensaje
                });
                document.querySelector('#fecha_hora').value = ''; // Limpiar el campo de fecha y hora
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
