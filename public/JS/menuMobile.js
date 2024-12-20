const toto = document.querySelector('#hamb');
const tata = document.querySelector('.nav');

toto.addEventListener('click', () => {
    tata.classList.toggle('active')
})
