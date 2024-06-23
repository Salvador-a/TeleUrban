import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.querySelector('#formulario-contacto');
    const botonAgendar = document.querySelector('input[type="button"][value="Agendar"]');

    if (formulario && botonAgendar) {
        botonAgendar.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((confirmResult) => {
                if (confirmResult.isConfirmed) {
                    const formData = new FormData(formulario);
                    fetch(formulario.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.error) {
                            Swal.fire({
                                title: 'Error',
                                text: result.mensaje,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                if (result.form_data) {
                                    for (const [key, value] of Object.entries(result.form_data)) {
                                        if (formulario.elements[key]) {
                                            formulario.elements[key].value = value;
                                        }
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                title: '¡Enviado!',
                                text: result.mensaje,
                                icon: 'success'
                            }).then(() => {
                                window.location.href = result.redirect;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al enviar el formulario.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
            });
        });
    }
});
