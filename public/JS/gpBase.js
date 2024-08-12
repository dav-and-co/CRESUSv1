let nav = document.querySelector('nav')
window.addEventListener('scroll', () => {
    if(window.scrollY > 20){
        nav.style.backgroundColor = "#F7FAFB";
    }
    else{
        nav.style.backgroundColor = "transparent";
    }
})