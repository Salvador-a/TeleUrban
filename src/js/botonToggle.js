document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('btn-toggle');
    
    if (toggle) {
        toggle.addEventListener('change', function() {
            if (this.checked) {
                console.log('Toggle ON');
            } else {
                console.log('Toggle OFF');
            }
        });
    } else {
        console.error('Elemento con id "btn-toggle" no encontrado.');
    }
});
