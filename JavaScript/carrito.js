// carrito.js

class Carrito {
    constructor() {
        this.items = this.obtenerCarritoLocalStorage();
        this.actualizarContadorCarrito();
    }

    // Obtener carrito del localStorage
    obtenerCarritoLocalStorage() {
        const carritoGuardado = localStorage.getItem('carrito');
        return carritoGuardado ? JSON.parse(carritoGuardado) : [];
    }

    // Guardar carrito en localStorage
    guardarCarritoLocalStorage() {
        localStorage.setItem('carrito', JSON.stringify(this.items));
        this.actualizarContadorCarrito();
    }

    // Actualizar contador en el navegador (MEJORADO - aplica a todas las páginas)
    actualizarContadorCarrito() {
        const totalItems = this.items.reduce((total, item) => total + item.cantidad, 0);
        const contadores = document.querySelectorAll('.contador-carrito');
        
        contadores.forEach(contador => {
            contador.textContent = totalItems;
            contador.style.display = totalItems > 0 ? 'inline-block' : 'none';
        });

        // También actualizar en el localStorage para otras páginas
        localStorage.setItem('carritoCount', totalItems);
    }

    // Agregar producto al carrito
    agregarProducto(nombre, precio, imagen, descuento = null) {
        if (!nombre || !precio || !imagen) {
            console.error('Datos del producto incompletos:', { nombre, precio, imagen });
            return;
        }

        const precioNumerico = parseFloat(precio);
        if (isNaN(precioNumerico)) {
            console.error('Precio inválido:', precio);
            return;
        }

        const productoExistente = this.items.find(item => item.nombre === nombre);
        
        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            this.items.push({
                nombre: nombre,
                precio: precioNumerico,
                precioOriginal: descuento ? precioNumerico / (1 - (descuento / 100)) : precioNumerico,
                imagen: imagen,
                cantidad: 1,
                descuento: descuento
            });
        }
        
        this.guardarCarritoLocalStorage();
        this.mostrarMensaje(`${nombre} agregado al carrito`);
    }

    // Eliminar producto del carrito
    eliminarProducto(nombre) {
        this.items = this.items.filter(item => item.nombre !== nombre);
        this.guardarCarritoLocalStorage();
        this.mostrarCarrito();
    }

    // Actualizar cantidad de producto
    actualizarCantidad(nombre, nuevaCantidad) {
        if (nuevaCantidad <= 0) {
            this.eliminarProducto(nombre);
            return;
        }

        const producto = this.items.find(item => item.nombre === nombre);
        if (producto) {
            producto.cantidad = nuevaCantidad;
            this.guardarCarritoLocalStorage();
            this.mostrarCarrito();
        }
    }

    // Calcular total
    calcularTotal() {
        return this.items.reduce((total, item) => {
            return total + (item.precio * item.cantidad);
        }, 0);
    }

    // Calcular subtotal
    calcularSubtotal() {
        return this.calcularTotal();
    }

    // Mostrar mensaje de confirmación
    mostrarMensaje(mensaje) {
        const mensajeElemento = document.createElement('div');
        mensajeElemento.textContent = mensaje;
        mensajeElemento.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: #ffffff;
            color: #050524;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        `;

        document.body.appendChild(mensajeElemento);

        setTimeout(() => {
            mensajeElemento.remove();
        }, 3000);
    }

    // Mostrar carrito en página de carrito (MEJORADO)
    mostrarCarrito() {
        const contenedorCarrito = document.querySelector('.obgeto_carrito');
        const resumenCarrito = document.querySelector('.resumen_carrito');

        if (!contenedorCarrito || !resumenCarrito) return;

        // Mostrar items del carrito
        if (this.items.length === 0) {
            contenedorCarrito.innerHTML = `
                <div class="carrito-vacio">
                    <div class="icono-carrito-vacio">🛒</div>
                    <h3>Tu carrito está vacío</h3>
                    <p>Agrega algunos juegos increíbles para comenzar</p>
                    <a href="./catalogo.html" class="boton_compra">Descubrir Juegos</a>
                </div>
            `;
            resumenCarrito.innerHTML = '';
            return;
        }

        // Mostrar items del carrito
        contenedorCarrito.innerHTML = this.items.map(item => {
            const nombreEscapado = this.escapeHtml(item.nombre);
            
            return `
                <div class="item-carrito">
                    <img src="${item.imagen}" alt="${item.nombre}" onerror="this.src='../Imagenes/error_imagen.jpg'">
                    <div class="info-item">
                        <h4>${item.nombre}</h4>
                        <div class="precios">
                            ${item.descuento ? `
                                <span class="precio_original">$${item.precioOriginal.toFixed(2)}</span>
                                <span class="precio_descuento">$${item.precio.toFixed(2)}</span>
                                <span class="descuento">-${item.descuento}%</span>
                            ` : `
                                <span class="precio_normal">$${item.precio.toFixed(2)}</span>
                            `}
                        </div>
                    </div>
                    <div class="controles-cantidad">
                        <button class="btn-cantidad" data-action="decrease" data-nombre="${nombreEscapado}">-</button>
                        <span class="cantidad">${item.cantidad}</span>
                        <button class="btn-cantidad" data-action="increase" data-nombre="${nombreEscapado}">+</button>
                    </div>
                    <div class="subtotal">$${(item.precio * item.cantidad).toFixed(2)}</div>
                    <button class="btn-eliminar" data-nombre="${nombreEscapado}" title="Eliminar">×</button>
                </div>
            `;
        }).join('');

        // Agregar event listeners a los botones dinámicos
        this.agregarEventListenersDinamicos();

        // Mostrar resumen MEJORADO (solo una línea de separación)
        const subtotal = this.calcularSubtotal();
        const total = this.calcularTotal();
        const totalItems = this.items.reduce((sum, item) => sum + item.cantidad, 0);

        resumenCarrito.innerHTML = `
            <div class="resumen">
                <h3>Resumen de Compra</h3>
                <div class="detalles-resumen">
                    <div class="linea-resumen">
                        <span>Productos (${totalItems}):</span>
                        <span>$${subtotal.toFixed(2)}</span>
                    </div>
                    <div class="linea-resumen">
                        <span>Envío:</span>
                        <span>Gratis</span>
                    </div>
                    <div class="linea-resumen">
                        <span>Impuestos:</span>
                        <span>Incluidos</span>
                    </div>
                    <div class="separador"></div>
                    <div class="linea-resumen total">
                        <span><strong>Total:</strong></span>
                        <span><strong>$${total.toFixed(2)}</strong></span>
                    </div>
                </div>
                <button class="boton_compra btn-pagar" id="btnPagar">Proceder al Pago</button>
                <button class="btn-vaciar-carrito" id="btnVaciar">Vaciar Carrito</button>
            </div>
        `;

        // Agregar event listeners
        document.getElementById('btnPagar')?.addEventListener('click', () => {
            this.procesarPago();
        });

        document.getElementById('btnVaciar')?.addEventListener('click', () => {
            this.vaciarCarrito();
        });
    }

    // Escapar HTML para prevenir errores
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Agregar event listeners dinámicos
    agregarEventListenersDinamicos() {
        document.querySelectorAll('.btn-cantidad').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const nombre = e.target.getAttribute('data-nombre');
                const action = e.target.getAttribute('data-action');
                const item = this.items.find(item => item.nombre === nombre);
                
                if (item) {
                    if (action === 'increase') {
                        this.actualizarCantidad(nombre, item.cantidad + 1);
                    } else if (action === 'decrease') {
                        this.actualizarCantidad(nombre, item.cantidad - 1);
                    }
                }
            });
        });

        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const nombre = e.target.getAttribute('data-nombre');
                this.eliminarProducto(nombre);
            });
        });
    }

    // Procesar pago
    procesarPago() {
        if (this.items.length === 0) {
            this.mostrarMensaje('Tu carrito está vacío');
            return;
        }

        const total = this.calcularTotal();
        if (confirm(`¿Proceder con el pago de $${total.toFixed(2)}?`)) {
            this.mostrarMensaje('¡Compra realizada con éxito! Los juegos están en tu biblioteca.');
            this.items = [];
            this.guardarCarritoLocalStorage();
            this.mostrarCarrito();
        }
    }

    // Vaciar carrito
    vaciarCarrito() {
        if (this.items.length === 0) {
            this.mostrarMensaje('El carrito ya está vacío');
            return;
        }
        
        if (confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
            this.items = [];
            this.guardarCarritoLocalStorage();
            this.mostrarCarrito();
            this.mostrarMensaje('Carrito vaciado');
        }
    }
}

// Inicializar carrito
const carrito = new Carrito();

// Agregar event listeners cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Agregar contadores al navegador en TODAS las páginas
    agregarContadoresCarrito();
    
    // Configurar botones de agregar al carrito
    configurarBotonesCarrito();
    
    // Mostrar carrito si estamos en la página del carrito
    if (document.querySelector('.obgeto_carrito')) {
        carrito.mostrarCarrito();
    }
});

// Agregar contadores de carrito al navegador en TODAS las páginas
function agregarContadoresCarrito() {
    const navItems = document.querySelectorAll('nav ul li a[href*="carrito.html"]');
    
    navItems.forEach(navItem => {
        if (navItem.querySelector('.contador-carrito')) return;
        
        const contador = document.createElement('span');
        contador.className = 'contador-carrito';
        contador.textContent = '0';
        navItem.appendChild(contador);
    });
    
    // Actualizar contador inicial
    carrito.actualizarContadorCarrito();
}

// Configurar botones de agregar al carrito
function configurarBotonesCarrito() {
    document.addEventListener('click', function(e) {
        const boton = e.target.closest('.boton_compra') || e.target.closest('.boton_tabla');
        
        if (boton) {
            e.preventDefault();
            e.stopPropagation();
            
            try {
                const tarjeta = boton.closest('.targeta_jugo') || boton.closest('tr');
                if (!tarjeta) {
                    console.error('No se encontró la tarjeta del producto');
                    return;
                }

                let nombre = '';
                const nombreElement = tarjeta.querySelector('h3') || 
                                   tarjeta.querySelector('.infomacion_tabla span') ||
                                   tarjeta.querySelector('td:nth-child(2)') ||
                                   tarjeta.querySelector('.nombre-juego');
                
                if (nombreElement) {
                    nombre = nombreElement.textContent.trim();
                } else {
                    console.error('No se pudo encontrar el nombre del producto');
                    return;
                }

                let precio = 0;
                let precioElement = tarjeta.querySelector('.precio_descuento') ||
                                 tarjeta.querySelector('.precio') ||
                                 tarjeta.querySelector('td:nth-child(4)') ||
                                 tarjeta.querySelector('.precio-normal');
                
                if (!precioElement) {
                    precioElement = tarjeta.querySelector('td:nth-child(3)') ||
                                 tarjeta.querySelector('td:nth-child(5)');
                }

                if (precioElement) {
                    const precioTexto = precioElement.textContent.replace(/[^\d.]/g, '');
                    precio = parseFloat(precioTexto);
                    if (isNaN(precio)) {
                        console.error('Precio inválido:', precioElement.textContent);
                        return;
                    }
                } else {
                    console.error('No se pudo encontrar el precio del producto');
                    return;
                }

                let imagen = '';
                const imgElement = tarjeta.querySelector('img');
                if (imgElement) {
                    imagen = imgElement.src;
                } else {
                    imagen = '../Imagenes/error_imagen.jpg';
                }

                let descuento = null;
                const descuentoElement = tarjeta.querySelector('.descuento') || 
                                      tarjeta.querySelector('td:nth-child(3)') ||
                                      tarjeta.querySelector('.descuento-badge');
                
                if (descuentoElement) {
                    const descuentoTexto = descuentoElement.textContent;
                    const match = descuentoTexto.match(/-?(\d+)%/);
                    if (match) {
                        descuento = parseInt(match[1]);
                    }
                }

                carrito.agregarProducto(nombre, precio, imagen, descuento);
                
            } catch (error) {
                console.error('Error al agregar producto:', error);
                carrito.mostrarMensaje('Error al agregar producto');
            }
        }
    });
}

// Agregar estilos CSS para las animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);