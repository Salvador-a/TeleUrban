// Espera a que todo el DOM esté cargado antes de ejecutar el código
document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar la página del formulario correspondiente
    function mostrarPagina(pagina) {
        // Selecciona todas las páginas del formulario y les quita la clase activa
        document.querySelectorAll('.formulario__pagina').forEach(paginaElemento => {
            paginaElemento.classList.remove('formulario__pagina--activa');
        });

        // Añade la clase activa a la página seleccionada
        const paginaActiva = document.getElementById('pagina' + pagina);
        if (paginaActiva) {
            paginaActiva.classList.add('formulario__pagina--activa');
        } else {
            console.error('Elemento con id "pagina' + pagina + '" no encontrado.');
        }
    }

    // Hace la función mostrarPagina disponible globalmente
    window.mostrarPagina = mostrarPagina;

    // Configuración del calendario con flatpickr
    flatpickr('#fecha_hora', {
        locale: 'es', // Idioma en español
        enableTime: true, // Habilita la selección de hora
        dateFormat: "Y-m-d h:i K", // Formato de fecha y hora
        minDate: "today", // La fecha mínima es hoy
        time_24hr: false, // Usa formato de 12 horas
        minTime: "10:00", // Hora mínima permitida
        maxTime: "16:00", // Hora máxima permitida
        minuteIncrement: 60, // Incremento de minutos en 60
        disable: [
            function (date) { // Deshabilita fechas específicas
                const today = new Date();
                if (date < today) {
                    return true; // Deshabilita fechas pasadas
                } else if (date.getDay() === 0 || date.getDay() === 6) {
                    return true; // Deshabilita fines de semana
                } else if (date.toDateString() === today.toDateString() && date.getHours() >= 16) {
                    return true; // Deshabilita horas pasadas de hoy
                }
                return false;
            }
        ],
        onClose: function (selectedDates, dateStr, instance) { // Evento cuando se cierra el calendario
            let selectedDate = selectedDates[0];
            if (selectedDate) {
                if (selectedDate.getMinutes() !== 0) selectedDate.setMinutes(0); // Ajusta los minutos a 0 si no es una hora exacta
                if (selectedDate.getHours() < 10) selectedDate.setHours(10); // Ajusta horas menores a 10 a 10
                else if (selectedDate.getHours() >= 16) selectedDate.setHours(16); // Ajusta horas mayores o iguales a 16 a 16
                instance.setDate(selectedDate, true); // Establece la fecha ajustada en el calendario
                validarFechaHora(dateStr); // Valida la fecha y hora seleccionadas
            }
        }
    });

    // Función para validar la fecha y hora seleccionadas contra el servidor
    function validarFechaHora(fechaHora) {
        const areaId = document.querySelector('#area_id').value; // Obtiene el área seleccionada
        fetch('/validar-fecha-hora', {
            method: 'POST', // Usa el método POST
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fecha_hora: fechaHora, area_id: areaId }) // Envía la fecha y área en formato JSON
        })
        .then(response => response.json()) // Convierte la respuesta a JSON
        .then(data => {
            if (data.error) {
                Swal.fire({ icon: 'error', title: 'Error', text: data.mensaje });
                document.querySelector('#fecha_hora').value = ''; // Limpia el campo de fecha y hora en caso de error
            }
        })
        .catch(error => console.error('Error:', error)); // Maneja errores de la solicitud
    }

    // Inicializa la funcionalidad de autocompletado de emails
    function initAutocompletadoEmail() {
        const emailInput = document.getElementById('email'); // Selecciona el campo de email
        const suggestionBox = document.createElement('div'); // Crea un contenedor para las sugerencias
        suggestionBox.classList.add('suggestion-box'); // Añade una clase al contenedor de sugerencias
        emailInput.parentNode.appendChild(suggestionBox); // Añade el contenedor al DOM

        // Evento de entrada en el campo de email
        emailInput.addEventListener('input', function () {
            const value = emailInput.value;
            const atPosition = value.lastIndexOf('@');
            suggestionBox.innerHTML = '';

            if (atPosition !== -1) {
                const userInput = value.slice(0, atPosition + 1);
                const domainSuggestions = ['gmail.com', 'outlook.com', 'hotmail.com', 'yahoo.com', 'icloud.com', 'aol.com'];

                domainSuggestions.forEach(domain => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('suggestion-item');
                    suggestionItem.textContent = userInput + domain;
                    suggestionItem.addEventListener('click', function () {
                        emailInput.value = suggestionItem.textContent;
                        suggestionBox.innerHTML = '';
                        emailInput.dispatchEvent(new Event('input'));
                    });
                    suggestionBox.appendChild(suggestionItem);
                });
                suggestionBox.style.display = 'block';
            } else {
                suggestionBox.style.display = 'none';
            }
        });

        // Evento de teclas en el campo de email
        emailInput.addEventListener('keydown', function (e) {
            const items = suggestionBox.querySelectorAll('.suggestion-item');
            let index = Array.from(items).findIndex(item => item.classList.contains('suggestion-item--active'));

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (index < items.length - 1) {
                    if (index !== -1) items[index].classList.remove('suggestion-item--active');
                    items[index + 1].classList.add('suggestion-item--active');
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (index > 0) {
                    items[index].classList.remove('suggestion-item--active');
                    items[index - 1].classList.add('suggestion-item--active');
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (index !== -1) {
                    emailInput.value = items[index].textContent;
                    suggestionBox.innerHTML = '';
                    emailInput.dispatchEvent(new Event('input'));
                }
            }
        });

        // Evento de clic en el documento
        document.addEventListener('click', function (e) {
            if (!suggestionBox.contains(e.target) && e.target !== emailInput) {
                suggestionBox.style.display = 'none';
            }
        });
    }

    // Función para validar campos
    function validarCampo(campo, tipo) {
        let esValido = false;
        const valor = campo.value.trim();
        const validaciones = {
            'text': () => valor !== '',
            'textarea': () => valor !== '',
            'email': () => /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(valor),
            'tel': () => /^\d{10}$/.test(valor),
            'select-one': () => valor !== '',
            'file': () => campo.files[0] && campo.files[0].size <= 1.5 * 1024 * 1024
        };

        esValido = validaciones[tipo] ? validaciones[tipo]() : false;
        const icono = campo.parentNode.querySelector('.icono-validacion');
        campo.classList.toggle('formulario__entrada--error', !esValido);
        campo.classList.toggle('formulario__entrada--correcto', esValido);
        icono.classList.toggle('icono-validacion--error', !esValido);
        icono.classList.toggle('icono-validacion--correcto', esValido);
        icono.innerHTML = `<i class="material-icons">${esValido ? 'check_circle' : 'cancel'}</i>`;
    }

    // Función para agregar eventos de validación a los campos
    function agregarEventosValidacion(campo, tipo) {
        campo.addEventListener('input', () => {
            validarCampo(campo, tipo);
            actualizarProgreso1();
        });
        campo.addEventListener('blur', () => {
            validarCampo(campo, tipo);
            actualizarProgreso1();
        });
    }

    // Inicializa el formulario y agrega eventos de validación
    const formulario = document.querySelector('#formulario-contacto, #formulario-editar');
    if (formulario) {
        // Campos de la primera parte del formulario
        const camposParte1 = [
            'nombre',
            'a_paterno',
            'a_materno',
            'email',
            'telefono',
            'discapacidad_id',
            'genero_id',
            'semestre_id',
            'universidad_id',
            'habilidades',
            'curriculum'
        ].map(id => formulario.querySelector(`#${id}`)); // Selecciona todos los campos por su ID

        camposParte1.forEach(campo => {
            const icono = document.createElement('span'); // Crea un icono de validación
            icono.classList.add('icono-validacion'); // Añade clase al icono
            campo.parentNode.appendChild(icono); // Añade el icono al DOM
            const tipo = campo.type === 'select-one' ? 'select-one' : campo.type; // Determina el tipo de campo
            agregarEventosValidacion(campo, tipo); // Añade eventos de validación
        });

        formulario.querySelector('#curriculum').addEventListener('change', () => {
            validarCampo(campo, 'file'); // Valida el campo de archivo en cada cambio
            actualizarProgreso1(); // Actualiza el progreso
        });

        // Campos de la segunda parte del formulario
        const camposParte2 = [
            'fecha_hora',
            'departamento_id',
            'modalidad_id'
        ].map(id => formulario.querySelector(`#${id}`)); // Selecciona todos los campos por su ID

        camposParte2.forEach(campo => {
            const icono = document.createElement('span'); // Crea un icono de validación
            icono.classList.add('icono-validacion'); // Añade clase al icono
            campo.parentNode.appendChild(icono); // Añade el icono al DOM
            const tipo = campo.type === 'select-one' ? 'select-one' : campo.type; // Determina el tipo de campo
            campo.addEventListener('input', () => {
                validarCampo(campo, tipo);
                actualizarProgreso2();
            });
            campo.addEventListener('blur', () => {
                validarCampo(campo, tipo);
                actualizarProgreso2();
            });
        });
    }

    // Función para actualizar la barra de progreso de la primera parte del formulario
    function actualizarProgreso1() {
        const camposParte1 = [
            { selector: '#nombre', tipo: 'text' },
            { selector: '#a_paterno', tipo: 'text' },
            { selector: '#a_materno', tipo: 'text' },
            { selector: '#email', tipo: 'email' },
            { selector: '#telefono', tipo: 'tel' },
            { selector: '#discapacidad_id', tipo: 'select-one' },
            { selector: '#genero_id', tipo: 'select-one' },
            { selector: '#semestre_id', tipo: 'select-one' },
            { selector: '#universidad_id', tipo: 'select-one' },
            { selector: '#habilidades', tipo: 'textarea' },
            { selector: '#curriculum', tipo: 'file' }
        ];

        const totalCampos = camposParte1.length; // Número total de campos en la primera parte
        const camposLlenos = camposParte1.filter(campo => {
            const el = document.querySelector(campo.selector);
            if (campo.tipo === 'file') {
                return el.files.length > 0 && el.files[0].size <= 1.5 * 1024 * 1024; // Validación del tamaño del archivo
            } else {
                return el.value.trim() !== ''; // Validación de que el campo no esté vacío
            }
        }).length;

        const porcentaje = (camposLlenos / totalCampos) * 100; // Calcula el porcentaje de campos llenos
        document.getElementById('progreso-paso1').style.width = `${porcentaje}%`; // Actualiza el ancho de la barra de progreso
        document.getElementById('paso1').classList.toggle('completado', porcentaje === 100); // Añade o quita la clase completado
    }

    // Función para actualizar la barra de progreso de la segunda parte del formulario
    function actualizarProgreso2() {
        const camposParte2 = [
            { selector: '#fecha_hora', tipo: 'text' },
            { selector: '#departamento_id', tipo: 'select-one' },
            { selector: '#modalidad_id', tipo: 'select-one' }
        ];

        const totalCampos = camposParte2.length; // Número total de campos en la segunda parte
        const camposLlenos = camposParte2.filter(campo => document.querySelector(campo.selector).value.trim() !== '').length;

        const porcentaje = (camposLlenos / totalCampos) * 100; // Calcula el porcentaje de campos llenos
        document.getElementById('progreso-paso2').style.width = `${porcentaje}%`; // Actualiza el ancho de la barra de progreso
        document.getElementById('paso2').classList.toggle('completado', porcentaje === 100); // Añade o quita la clase completado
    }

    // Inicializa el autocompletado de email
    initAutocompletadoEmail();
});
