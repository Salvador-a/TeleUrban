import Swal from 'sweetalert2';
import { handleFormSubmit } from './helpers';

document.addEventListener('DOMContentLoaded', function() {
    console.log("Documento cargado, script editarCita.js ejecutado"); // Log para verificar que el script se ejecuta
    const formulario = document.querySelector('#formulario-editar');
    
    if (formulario) {
        console.log("Formulario encontrado"); // Log para verificar que el formulario es encontrado
        const botonActualizar = formulario.querySelector('#boton-actualizar');

        if (botonActualizar) {
            console.log("Botón de actualizar encontrado"); // Log para verificar que el botón es encontrado
            botonActualizar.addEventListener('click', function(e) {
                handleFormSubmit(e, formulario, 'actualizar');
            });
        }
    }
});
