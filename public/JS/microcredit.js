document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.toggle');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const chevron = this.querySelector('.chevron');

            chevron.classList.toggle('active');
            content.classList.toggle('active');
            // Alterne l'affichage du contenu
        });
    });
});

