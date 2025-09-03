import './bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    let currentSlide = 0;
    const slider = document.getElementById("slider");
    const slides = slider.querySelectorAll("img");
    const totalSlides = slides.length;

    function showSlide(index) {
        slider.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }

    // Expose functions so buttons can use them
    window.nextSlide = nextSlide;
    window.prevSlide = prevSlide;

    // Auto play
    setInterval(nextSlide, 5000);
});
