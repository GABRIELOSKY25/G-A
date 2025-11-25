// carrusel.js
function inicializarCarrusel() {
    const carrusel = document.querySelector('.carrusel');
    if (!carrusel) {
        console.log('No se encontró el carrusel');
        return;
    }

    const slides = carrusel.querySelectorAll('.deslizante');
    const totalSlides = slides.length;
    
    if (totalSlides === 0) {
        console.log('No se encontraron imágenes en el carrusel');
        return;
    }

    let currentSlide = 0;
    let intervalo;

    // Crear indicadores
    const indicadoresContainer = document.createElement('div');
    indicadoresContainer.className = 'indicadores-carrusel';
    
    for (let i = 0; i < totalSlides; i++) {
        const indicador = document.createElement('div');
        indicador.className = `indicador ${i === 0 ? 'active' : ''}`;
        indicador.addEventListener('click', () => {
            clearInterval(intervalo);
            showSlide(i);
            iniciarIntervalo();
        });
        indicadoresContainer.appendChild(indicador);
    }
    
    carrusel.appendChild(indicadoresContainer);
    const indicadores = indicadoresContainer.querySelectorAll('.indicador');

    // Función para mostrar slide
    function showSlide(n) {
        // Remover clase active de todos los slides e indicadores
        slides.forEach(slide => slide.classList.remove('active'));
        indicadores.forEach(ind => ind.classList.remove('active'));
        
        // Calcular nuevo índice
        currentSlide = (n + totalSlides) % totalSlides;
        
        // Agregar clase active al slide actual
        slides[currentSlide].classList.add('active');
        indicadores[currentSlide].classList.add('active');
    }

    // Función para iniciar intervalo automático
    function iniciarIntervalo() {
        intervalo = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 4000); // Cambia cada 4 segundos
    }

    // Iniciar el carrusel
    showSlide(0);
    iniciarIntervalo();

    // Pausar al hacer hover
    carrusel.addEventListener('mouseenter', () => {
        clearInterval(intervalo);
    });

    carrusel.addEventListener('mouseleave', () => {
        iniciarIntervalo();
    });

    console.log('Carrusel inicializado con', totalSlides, 'imágenes');
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(inicializarCarrusel, 500);
});

// Exportar para uso manual
window.inicializarCarrusel = inicializarCarrusel;