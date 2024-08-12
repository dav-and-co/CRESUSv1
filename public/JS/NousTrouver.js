document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('demandeur-form');
    const gdprCheckbox = document.getElementById('form_gdprAccepted');

    form.addEventListener('submit', function(event) {
        if (!gdprCheckbox.checked) {
            event.preventDefault(); // Empêche la soumission du formulaire
            alert('Vous devez accepter les conditions GDPR pour envoyer la demande.');
        }
    });
});