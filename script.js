const scrollContainer = document.querySelector('.horizontal-scroll');
const scrollLeftBtn = document.querySelector('.scroll-left');
const scrollRightBtn = document.querySelector('.scroll-right');

scrollLeftBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
        left: -200, 
        behavior: 'smooth'
    });
});

scrollRightBtn.addEventListener('click', () => {
    scrollContainer.scrollBy({
        left: 200, 
        behavior: 'smooth'
    });
});

function scrollHorizontally(distance) {
    const container = document.querySelector('.horizontal-scroll');
    container.style.transform = `translateX(${distance}px)`;
}
