document.addEventListener('DOMContentLoaded', function() {
    function filtrarEntrevistas(estado) {
        var rows = document.querySelectorAll('.table__tr');
        rows.forEach(function(row) {
            if (estado === 'todos') {
                row.style.display = '';
            } else {
                row.style.display = row.getAttribute('data-status') === estado ? '' : 'none';
            }
        });

        // Cambiar la clase 'active' del botón
        var buttons = document.querySelectorAll('.dashboard__filtro');
        buttons.forEach(function(button) {
            button.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    // Asignar la función a los botones de filtro
    var filterButtons = document.querySelectorAll('.dashboard__filtro');
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            filtrarEntrevistas(button.getAttribute('data-filter'));
        });
    });
});
