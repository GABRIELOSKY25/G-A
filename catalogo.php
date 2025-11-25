<?php
// catalogo.php

// 1. Incluir el Modelo (Esto actúa como nuestro Controlador)
require_once 'src/JuegoModel.php';

$juegoModel = new JuegoModel();
// Obtener juegos para el catálogo completo
$catalogo_juegos = $juegoModel->obtenerTodosLosJuegos(); 
// Obtener juegos para la sección de destacados (ejemplo)
$juegos_destacados = $juegoModel->obtenerJuegosDestacados(3); 

// Helper para ajustar la ruta de la imagen (la DB tiene 'img/' y tu HTML usa '../Imagenes/Juegos/')
function obtenerRutaImagen($db_imagen) {
    // Reemplaza 'img/' con la ruta base de tu HTML
    $ruta_base = '../Imagenes/Juegos/';
    // El archivo de la DB es 'img/nombre.webp', lo convertimos a 'nombre.webp'
    $nombre_archivo = str_replace('img/', '', $db_imagen);
    
    // Asumiendo que tus imágenes tienen la extensión correcta en el path de la DB
    // Ejemplo: '../Imagenes/Juegos/spiderman2.webp'
    return $ruta_base . $nombre_archivo;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title> Venta </title> 
    <link rel = "icon" href = "../Logo_Principal.ico" type = "image/x-icon">
    <link rel = "stylesheet" href = "CSS/catalogo.css">
</head>

<body>
    <header>
        <div class = "menu_navegacion">
            <div class = "contenedor">
                <div class = "logo">
                    <a href = "../index.html">
                        <img src = "../Logo_Principal.png" alt = "G&A" style = "width: 60px; height: auto;">
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href = "../index.html"> Inicio </a></li>
                        <li><a href = "catalogo.php"> Catalogo </a></li> <li><a href = "oferta.html"> Ofertas </a></li>
                        <li><a href = "ubicacion.html"> Ubicacion </a></li>
                        <li><a href = "carrito.html"> Carrito </a></li>
                        <li><a href = "cuenta.php"> Cuenta </a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <section class = "titulo">
            <div class = "contenedor">
                <h1> Explora nuestros juegos </h1>
            </div>
        </section>

        <section class = "juegos_destacados">
            <div class = "contenedor">
                <h2> Juegos Destacados </h2>
                <div class = "cuadricula_juegos">
                    <?php foreach ($juegos_destacados as $juego): ?>
                        <article class = "targeta_jugo">
                            <img src = "<?php echo htmlspecialchars(obtenerRutaImagen($juego['imagen'])); ?>" 
                                 alt = "Portada de juego <?php echo htmlspecialchars($juego['nombre']); ?>">
                            <h3> <?php echo htmlspecialchars($juego['nombre']); ?> </h3>
                            <p> <?php echo htmlspecialchars($juego['sinopsis']); ?> </p>
                            <?php 
                                $precio_final = $juego['precio'];
                                if ($juego['oferta'] > 0) {
                                    $descuento = $juego['precio'] * ($juego['oferta'] / 100);
                                    $precio_final = $juego['precio'] - $descuento;
                                    echo '<div class="precio"> <s style="color: #999;">$' . number_format($juego['precio'], 2) . '</s> $' . number_format($precio_final, 2) . '</div>';
                                } else {
                                    echo '<div class="precio"> $' . number_format($precio_final, 2) . ' </div>';
                                }
                            ?>
                            <button class = "boton_compra"> Agregar al carrito </button>
                        </article>
                    <?php endforeach; ?>
                    </div>
            </div>
        </section>

        <section class = "catalogo">
            <div class = "contenedor">
                <h2> Catalogo de juegos </h2>
                
                <div class = "filtros">
                    <select id = "filtro_categoria">
                        <option value = "todas"> Todas las categorías </option>
                        <option value = "accion"> Acción </option>
                        <option value = "aventura"> Aventura </option>
                        </select>

                    <select id = "filtro_precio">
                        <option value = "todos"> Todos los precios </option>
                        <option value = "0-200"> $0 - $200 </option>
                        </select>

                    <select id = "filtro_plataforma">
                        <option value = "todos"> Todas las plataformas </option>
                        <option value = ""> PC </option>
                        </select>
                </div>

                <div class = "cuadricula_juegos">
                    <?php foreach ($catalogo_juegos as $juego): ?>
                        <article class = "targeta_jugo">
                            <img src = "<?php echo htmlspecialchars(obtenerRutaImagen($juego['imagen'])); ?>" 
                                 alt = "Portada de juego <?php echo htmlspecialchars($juego['nombre']); ?>">
                            <h3> <?php echo htmlspecialchars($juego['nombre']); ?> </h3>
                            <p> <?php echo htmlspecialchars($juego['sinopsis']); ?> </p>
                            <small style="color: #00d9ff; display: block; text-align: center;"> 
                                <?php echo htmlspecialchars($juego['plataforma'] . ' | ' . $juego['categoria']); ?> 
                            </small>
                            
                            <?php 
                                $precio_final = $juego['precio'];
                                if ($juego['oferta'] > 0) {
                                    $descuento = $juego['precio'] * ($juego['oferta'] / 100);
                                    $precio_final = $juego['precio'] - $descuento;
                                    echo '<div class="precio"> <s style="color: #999;">$' . number_format($juego['precio'], 2) . '</s> $' . number_format($precio_final, 2) . '</div>';
                                } else {
                                    echo '<div class="precio"> $' . number_format($precio_final, 2) . ' </div>';
                                }
                            ?>
                            <button class = "boton_compra"> Agregar al carrito </button>
                        </article>
                    <?php endforeach; ?>
                    </div>
            </div>
        </section>
    </main>

    <footer>
        <div class = "contenedor">
            <div class = "derechos">
                <p> &copy; 2025 G&A. Todos los derechos reservados </p>
            </div>
        </div>
    </footer>

    <script src = "../JavaScript/carrito.js"></script>
</body>
</html>