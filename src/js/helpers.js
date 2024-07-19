import Swal from 'sweetalert2';

export async function handleFormSubmit(e, formulario, action) {
    e.preventDefault();

    const formData = new FormData(formulario);
    formData.set('confirmado', 'false');

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
        let confirmTitle, confirmText, successTitle, successHtml;

        if (action === 'crear') {
            confirmTitle = '¿Estás seguro de agendar esta cita?';
            confirmText = '¡No podrás revertir esto!';
            successTitle = '¡Enviado!';
            successHtml = '<p>Tu formulario ha sido enviado exitosamente.</p><p class="subtitulo">Hemos enviado un correo electrónico a la dirección proporcionada. Por favor, revíselo.</p>';
        } else if (action === 'actualizar') {
            confirmTitle = '¿Estás seguro de actualizar esta cita?';
            confirmText = '¡No podrás revertir esto!';
            successTitle = '¡Actualizado!';
            successHtml = '<p>Tu formulario ha sido actualizado exitosamente.</p><p class="subtitulo">Hemos enviado un correo electrónico a la dirección proporcionada. Por favor, revíselo.</p>';
        }

        Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar'
        }).then((confirmResult) => {
            if (confirmResult.isConfirmed) {
                formData.set('confirmado', 'true');
                fetch(formulario.action, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                  .then(finalResult => {
                      if (!finalResult.error) {
                          Swal.fire({
                              title: successTitle,
                              html: successHtml,
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
}
