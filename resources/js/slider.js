document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('product-container');
    const btnLeft = document.getElementById('scroll-left');
    const btnRight = document.getElementById('scroll-right');

    if (container && btnLeft && btnRight) {
        btnRight.addEventListener('click', () => {
            container.scrollBy({ left: 320, behavior: 'smooth' });
        });

        btnLeft.addEventListener('click', () => {
            container.scrollBy({ left: -320, behavior: 'smooth' });
        });
    }
});