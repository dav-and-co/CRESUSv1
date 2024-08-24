document.querySelector('button').addEventListener('click', function() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        window.location.href = "{{ path('Accueil') }}";  // ma page accueil
    }
});