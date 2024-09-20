document.addEventListener('DOMContentLoaded', function () {
    // Sélectionne tous les éléments avec la classe 'toggle'
    const toggles = document.querySelectorAll('.toggle');

    // Pour chaque élément 'toggle', ajoute un écouteur d'événement 'click'
    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            // Sélectionne l'élément <div class="content"> suivant (qui est le contenu associé)
            const content = this.nextElementSibling;

            // Sélectionne le chevron (icône) dans l'élément 'toggle'
            const chevron = this.querySelector('.chevron');

            // Ajoute ou supprime la classe 'active' pour le chevron (pour inverser la direction)
            chevron.classList.toggle('active');

            // Ajoute ou supprime la classe 'active' pour le contenu (pour afficher ou masquer le texte)
            content.classList.toggle('active');
        });
    });
});
