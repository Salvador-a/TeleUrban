import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.querySelector('#formulario-contacto');
    const botonAgendar = document.querySelector('#boton-agendar');

    if (formulario) {
        botonAgendar.addEventListener('click', async function(e) {
            e.preventDefault();

            // Primero validar el formulario y obtener las alertas del servidor
            const formData = new FormData(formulario);
            formData.set('confirmado', 'false'); // Asegurar que el campo confirmado sea falso
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
                        // Cambiar el valor del campo confirmado y enviar el formulario
                        formData.set('confirmado', 'true');
                        fetch(formulario.action, {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json())
                          .then(finalResult => {
                              if (!finalResult.error) {
                                  Swal.fire({
                                      title: '¡Enviado!',
                                      html: '<p>Tu formulario ha sido enviado exitosamente.</p><p class="subtitulo">Hemos enviado un correo electrónico a la dirección proporcionada. Por favor, revíselo.</p>',
                                      icon: 'success',
                                      confirmButtonText: 'OK',
                                      customClass: {
                                          content: 'content-class'
                                      }
                                  }).then(() => {
                                      window.location.href = finalResult.redirect;
                                  });
                              } else {
                                  Swal.fire({
                                      title: 'Error',
                                      text: finalResult.mensaje,
                                      icon: 'error',
                                      confirmButtonText: 'OK'
                                  });
                              }
                          });
                    }
                });
            }
        });
    }
});
