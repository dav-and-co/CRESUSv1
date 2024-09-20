// Sélectionne l'élément 'nav', qui représente la barre de navigation dans le document.
let nav = document.querySelector('nav');

// Ajoute un écouteur d'événements à la fenêtre qui écoute l'événement 'scroll'.
// Chaque fois que l'utilisateur fait défiler la page, la fonction ci-dessous sera exécutée.
window.addEventListener('scroll', () => {
    // Affiche "test" dans la console pour vérifier que l'événement de défilement est bien déclenché.
    console.log("test");

    // Vérifie si la fenêtre a été défilée de plus de 20 pixels par rapport au haut de la page.
    if(window.scrollY > 20){
        // Si c'est le cas, change la couleur d'arrière-plan de la barre de navigation en "#f0f3f9".
        nav.style.backgroundColor = "#f0f3f9";
    }
    else{
        // Sinon, la barre de navigation reprend un arrière-plan transparent.
        nav.style.backgroundColor = "transparent";
    }
});
