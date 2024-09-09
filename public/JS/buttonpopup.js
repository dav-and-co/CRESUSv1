// stocke dans une variable les éléments qui ont pour classe "jsforclicpopup" (boutons pour afficher popup)
const deleteArticleButtons = document.querySelectorAll('.jsDeleteButton');

// pour chaque bouton de suppression trouvé
deleteArticleButtons.forEach((ArticleButton)=> {

    // event listener "click" : bouton soit cliqué => execute une fonction de callback
    ArticleButton.addEventListener('click', ()=> {

        // on prend l'élément HTML suivant (c'est à dire ici la popup)
        const popUp = ArticleButton.nextElementSibling;
        console.log(popUp)

        // et on l'affiche en modifiant son display en CSS
        popUp.style.display = 'block';
    });
})