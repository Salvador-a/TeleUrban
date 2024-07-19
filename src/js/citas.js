import Swal from 'sweetalert2';
import { handleFormSubmit } from './helpers';

document.addEventListener('DOMContentLoaded', function() {
    const formularios = document.querySelectorAll('#formulario-contacto');

    formularios.forEach(formulario => {
        const botonAgendar = formulario.querySelector('#boton-agendar');

        if (botonAgendar) {
            botonAgendar.addEventListener('click', function(e) {
                handleFormSubmit(e, formulario, 'crear');
            });
        }
    });
});
