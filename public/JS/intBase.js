let nav = document.querySelector('nav')
window.addEventListener('scroll', () => {
   console.log("test")
    if(window.scrollY > 20){
        nav.style.backgroundColor = "#f0f3f9";
    }
    else{
        nav.style.backgroundColor = "transparent";
    }
})
