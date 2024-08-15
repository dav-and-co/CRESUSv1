document.addEventListener('DOMContentLoaded', function () {
    const carouselInner = document.querySelector('.carousel-inner');
    const carouselItems = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.dot');
    const totalItems = carouselItems.length;
    let currentIndex = 0;

    document.querySelector('.next').addEventListener('click', function () {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    });

    document.querySelector('.prev').addEventListener('click', function () {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', function () {
            currentIndex = parseInt(dot.getAttribute('data-index'));
            updateCarousel();
        });
    });

    function updateCarousel() {
        const offset = -currentIndex * 100;
        carouselInner.style.transform = `translateX(${offset}%)`;

        // Mise à jour des points actifs
        dots.forEach(dot => dot.classList.remove('active'));
        dots[currentIndex].classList.add('active');
    }
});