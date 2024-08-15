document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.toggle');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const chevrons = this.querySelectorAll('.chevron');

            // Alterne l'affichage du contenu
            if (content.style.display === 'block') {
                content.style.display = 'none';
                chevrons[0].style.display = 'flex'; // Affiche le chevron bas
                chevrons[1].style.display = 'none';  // Masque le chevron haut
            } else {
                content.style.display = 'block';
                chevrons[0].style.display = 'none';  // Masque le chevron bas
                chevrons[1].style.display = 'flex'; // Affiche le chevron haut
            }
        });
    });
});

