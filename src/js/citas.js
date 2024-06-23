import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.querySelector('#formulario-contacto');
    if (formulario) {
        formulario.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(formulario);
            const response = await fetch(formulario.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.error) {
                if (result.mensaje) {
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
                } else if (result.alertas) {
                    const alertas = result.alertas.error.map(alerta => `<li>${alerta}</li>`).join('');
                    Swal.fire({
                        title: 'Errores en el formulario',
                        html: `<ul>${alertas}</ul>`,
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
                }
            } else {
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
                        Swal.fire({
                            title: '¡Enviado!',
                            text: 'Tu formulario ha sido enviado exitosamente.',
                            icon: 'success'
                        }).then(() => {
                            window.location.href = '/citas';
                        });
                    }
                });
            }
        });
    }


    
});
