// Lorsque le document est complètement chargé, exécute la fonction.
document.addEventListener('DOMContentLoaded', function () {

    // Sélectionne l'élément qui contient tous les éléments de la galerie (carousel).
    const carouselInner = document.querySelector('.carousel-inner');

    // Sélectionne tous les éléments individuels du carousel (les slides).
    const carouselItems = document.querySelectorAll('.carousel-item');

    // Sélectionne tous les points indicateurs (dots) en bas du carousel.
    const dots = document.querySelectorAll('.dot');

    // Calcule le nombre total d'éléments dans le carousel.
    const totalItems = carouselItems.length;

    // Initialise l'index de l'élément actuellement affiché à 0 (le premier slide).
    let currentIndex = 0;

    // Ajoute un écouteur d'événement au bouton 'next' pour avancer d'un slide lorsque cliqué.
    document.querySelector('.next').addEventListener('click', function () {

        // Incrémente l'index actuel et le ramène à 0 si on dépasse le dernier élément, grace au modulo.
        currentIndex = (currentIndex + 1) % totalItems;

        // Appelle la fonction pour mettre à jour l'affichage du carousel.
        updateCarousel();
    });

    // Ajoute un écouteur d'événement au bouton 'prev' pour reculer d'un slide lorsque cliqué.
    document.querySelector('.prev').addEventListener('click', function () {

        // Décrémente l'index actuel, en prenant soin de revenir au dernier slide si on est sur le premier, grace au modulo.
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;

        // Met à jour l'affichage du carousel.
        updateCarousel();
    });

    // Ajoute un écouteur d'événement pour chaque point (dot).
    dots.forEach(dot => {
        dot.addEventListener('click', function () {

            // Met à jour l'index courant en fonction du point cliqué, en récupérant la valeur 'data-index' de l'attribut du point.
            currentIndex = parseInt(dot.getAttribute('data-index'));

            // Met à jour l'affichage du carousel.
            updateCarousel();
        });
    });

    // Fonction pour mettre à jour la position et l'affichage du carousel.
    function updateCarousel() {

        // Calcule la nouvelle position en pourcentage pour déplacer l'élément contenant les slides.
        // Chaque slide est décalé de 100% horizontalement.
        const offset = -currentIndex * 100;

        // Applique la transformation pour déplacer le carousel dans la bonne position.
        carouselInner.style.transform = `translateX(${offset}%)`;

        // Mise à jour visuelle des points actifs : retire la classe 'active' de tous les points.
        dots.forEach(dot => dot.classList.remove('active'));

        // Ajoute la classe 'active' au point correspondant à l'index actuel.
        dots[currentIndex].classList.add('active');
    }
});
