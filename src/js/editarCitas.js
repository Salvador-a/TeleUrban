// public/js/editarCitas.js
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const botonEditarCita = document.querySelector('#boton-editar-cita');

    if (botonEditarCita) {
        botonEditarCita.addEventListener('click', async function(e) {
            e.preventDefault();

            const { value: token } = await Swal.fire({
                title: 'Ingresa tu token para editar la cita',
                input: 'text',
                inputLabel: 'Token',
                inputPlaceholder: 'Ingresa tu token',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Por favor, ingresa tu token';
                    }
                }
            });

            if (token) {
                const response = await fetch(`/citas/validar-token?token=${token}`);
                const result = await response.json();

                if (result.error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Token inválido o expirado',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Llenar el formulario con los datos de la cita
                    const formulario = document.querySelector('#formulario-contacto');
                    for (const [key, value] of Object.entries(result.data)) {
                        if (formulario.elements[key]) {
                            formulario.elements[key].value = value;
                        }
                    }
                    // Mostrar el formulario de edición
                    Swal.fire({
                        title: 'Token válido',
                        text: 'Puedes editar tu cita ahora',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    }
});
