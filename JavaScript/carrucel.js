document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.deslizante');
        const totalSlides = slides.length;
        
        // Función para mostrar slide
        function showSlide(n) {
            // Remover clase active de todos los slides
            slides.forEach(slide => slide.classList.remove('active'));
            
            // Calcular nuevo índice
            currentSlide = (n + totalSlides) % totalSlides;
            
            // Agregar clase active al slide actual
            slides[currentSlide].classList.add('active');
        }
        
        // Iniciar el carrusel mostrando el primer slide
        showSlide(0);
        
        // Cambiar slide automáticamente cada 5 segundos
        setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    });