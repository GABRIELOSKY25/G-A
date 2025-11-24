function inicializarCarrusel() {
    const slides = document.querySelectorAll('.deslizante');
    const totalSlides = slides.length;
    
    if (totalSlides === 0) {
        console.log('No se encontraron slides para el carrusel');
        return;
    }
    
    let currentSlide = 0;
    
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
    
    console.log('Carrusel inicializado con', totalSlides, 'slides');
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño delay para asegurar que Vue.js haya renderizado
    setTimeout(inicializarCarrusel, 100);
});

// También exportar la función para llamarla manualmente
window.inicializarCarrusel = inicializarCarrusel;