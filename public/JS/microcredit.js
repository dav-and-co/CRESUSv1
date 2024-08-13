document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.toggle');

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const content = this.nextElementSibling;
            if (content.style.display === 'block') {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
        });
    });
});

