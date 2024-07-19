(function() {
    const tagsInput = document.querySelector('#tags_input');

    if (tagsInput) {
        const tagsDiv = document.querySelector('#tags');
        const tagsInputHidden = document.querySelector('[name="tags"]');

        let tags = [];

        if(tagsInputHidden.value !== '') {
            tags = tagsInputHidden.value.split(',');
            mostrarTags();
        }

        tagsInput.addEventListener('keypress', guardarTag);

        function guardarTag(e) {
            if(e.keyCode === 44) {
                if(e.target.value.trim() === '' || e.target.value < 1) {
                    return;
                }

                e.preventDefault();

                tags = [...tags, e.target.value.trim() ]
                tagsInput.value = '';
                mostrarTags();
                validarTagsInput();
            }
        }

        function mostrarTags() {
            tagsDiv.textContent = '';
            tags.forEach(tag => {
                const etiqueta = document.createElement('LI');
                etiqueta.classList.add('Formulario__tag');
                etiqueta.textContent = tag;
                etiqueta.ondblclick = eliminarTag;
                tagsDiv.appendChild(etiqueta);
            })
            actualizarInputHidden();
        }

        function eliminarTag(e) {
            e.target.remove();
            tags = tags.filter(tag => tag !== e.target.textContent);
            actualizarInputHidden();
            validarTagsInput();
        }

        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString();
        }

        function validarTagsInput() {
            const icono = tagsInput.parentNode.querySelector('.icono-validacion');
            if (tags.length > 0) {
                tagsInput.classList.remove('formulario__entrada--error');
                tagsInput.classList.add('formulario__entrada--correcto');
                icono.classList.remove('icono-validacion--error');
                icono.classList.add('icono-validacion--correcto');
                icono.innerHTML = '<i class="material-icons">check_circle</i>';
            } else {
                tagsInput.classList.remove('formulario__entrada--correcto');
                tagsInput.classList.add('formulario__entrada--error');
                icono.classList.remove('icono-validacion--correcto');
                icono.classList.add('icono-validacion--error');
                icono.innerHTML = '<i class="material-icons">cancel</i>';
            }
        }

        const formulario = document.querySelector('#formulario-contacto, #formulario-editar');
        formulario.addEventListener('submit', (e) => {
            if (tags.length === 0) {
                e.preventDefault();
                tagsInput.setCustomValidity('Debe agregar al menos una área de experiencia.');
                tagsInput.reportValidity();
                validarTagsInput();
            } else {
                tagsInput.setCustomValidity('');
            }
        });
    } else {
        console.error('Elemento con id "tags_input" no encontrado.');
    }
})();
