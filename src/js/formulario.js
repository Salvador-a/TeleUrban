document.addEventListener('DOMContentLoaded', function () {
    function mostrarPagina(pagina) {
        var paginas = document.querySelectorAll('.formulario__pagina');
        
        paginas.forEach(function (paginaElemento) {
            paginaElemento.classList.remove('formulario__pagina--activa');
        });
        
        var paginaActiva = document.getElementById('pagina' + pagina);
        if (paginaActiva) {
            paginaActiva.classList.add('formulario__pagina--activa');
        }
    }

    window.mostrarPagina = mostrarPagina;
});
