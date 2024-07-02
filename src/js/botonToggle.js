document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('btn-toggle');
    
    toggle.addEventListener('change', function() {
        if (this.checked) {
            console.log('Toggle ON');
        } else {
            console.log('Toggle OFF');
        }
    });
});
