<?php
// catalogo.php

// Si usas sesiones en otras partes del sitio puedes descomentar esto
// session_start();

// 1. Incluir el Modelo
require_once __DIR__ . '/../src/JuegoModel.php';

$juegoModel = new JuegoModel();

// Obtener juegos para el catálogo completo
$catalogo_juegos   = $juegoModel->obtenerTodosLosJuegos();

// Obtener juegos para la sección de destacados (por ejemplo, 3 juegos)
$juegos_destacados = $juegoModel->obtenerJuegosDestacados(3);

// Helper para ajustar la ruta de la imagen
// En la BD tienes algo como 'img/spiderman2.webp'
// y en el HTML usas '../Imagenes/Juegos/spiderman2.webp'
function obtenerRutaImagen($db_imagen) {
    $ruta_base = '../Imagenes/Juegos/';
    $nombre_archivo = str_replace('img/', '', $db_imagen);
    return $ruta_base . $nombre_archivo;
}
?>
<!--Version de HTML-->
<!DOCTYPE html>
<!--Inicio de la pagiona web-->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Titutlo de Navegacion-->
    <title> Venta </title>
    <!-- Logo de Navegacion -->
    <link rel="icon" href="../Logo_Principal.ico" type="image/x-icon">
    <!-- Enlaces a hojas de estilo -->
    <link rel="stylesheet" href="../CSS/catalogo.css">
</head>

<!--Contenido de la Pagina-->
<body>
    <!--Menu de navegacion-->
    <header>
        <!--Contenido-->
        <div class="menu_navegacion">
            <div class="contenedor">
                <div class="logo">
                    <!--Ruta relativa-->
                    <a href="../index.html">
                        <!--Ruta de imagen // src = ruta // alt = nombre // style = (width = ancho // height = alto)-->
                        <img src="../Logo_Principal.png" alt="G&A" style="width: 60px; height: auto;">
                    </a>
                </div>

                <nav>
                    <!--Lista no ordenada-->
                    <ul>
                        <li><a href="../index.html"> Inicio </a></li>
                        <li><a href = "catalogo.php"> Catalogo </a></li>
                        <li><a href="oferta.php"> Ofertas </a></li>
                        <li><a href="ubicacion.html"> Ubicacion </a></li>
                        <li><a href="carrito.html"> Carrito </a></li>
                        <li><a href="cuenta.php"> Cuenta </a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <!--Titulo-->
        <section class="titulo">
            <div class="contenedor">
                <h1> Explora nuestros juegos </h1>
            </div>
        </section>

        <!--Seccion de juegos destacados (DINÁMICO)-->
        <section class="juegos_destacados">
            <div class="contenedor">
                <h2> Juegos Destacados </h2>
                <div class="cuadricula_juegos">
                    <?php if (!empty($juegos_destacados)): ?>
                        <?php foreach ($juegos_destacados as $juego): ?>
                            <article class="targeta_jugo">
                                <img src="<?php echo htmlspecialchars($juego['imagen']); ?>" 
                                    alt="Portada de juego <?php echo htmlspecialchars($juego['nombre']); ?>">

                                <h3><?php echo htmlspecialchars($juego['nombre']); ?></h3>
                                <p><?php echo htmlspecialchars($juego['sinopsis']); ?></p>

                                <?php 
                                    $precio_final = $juego['precio'];
                                    if ($juego['oferta'] > 0) {
                                        $descuento = $juego['precio'] * ($juego['oferta'] / 100);
                                        $precio_final = $juego['precio'] - $descuento;

                                        echo '<div class="precio">
                                                <s style="color: #999;">$'.number_format($juego['precio'],2).'</s>
                                                $'.number_format($precio_final,2).'
                                            </div>';
                                    } else {
                                        echo '<div class="precio">$'.number_format($precio_final,2).'</div>';
                                    }
                                ?>

                                <button class="boton_compra">Agregar al carrito</button>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay juegos destacados disponibles por el momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!--Sección del catálogo con filtros-->
        <section class="catalogo">
            <div class="contenedor">
                <h2> Catálogo de juegos </h2>

                <div class="filtros">
                    <select id="filtro_categoria">
                        <option value="todas">Todas las categorías</option>
                        <option value="accion">Acción</option>
                        <option value="aventura">Aventura</option>
                        <option value="carreras">Carreras</option>
                        <option value="deportes">Deportes</option>
                        <option value="disparos">Shooter</option>
                        <option value="rpg">RPG</option>
                        <option value="terror">Terror</option>
                    </select>

                    <select id="filtro_precio">
                        <option value="todos">Todos los precios</option>
                        <option value="0-200">$0 - $200</option>
                        <option value="200-600">$200 - $600</option>
                        <option value="600-1000">$600 - $1000</option>
                        <option value="1000-1500">$1000 - $1500</option>
                        <option value="1500-2000">$1500 - $2000</option>
                        <option value="2000+">$2000+</option>
                    </select>

                    <select id="filtro_plataforma">
                        <option value="todos">Todas las plataformas</option>
                        <option value="pc">PC</option>
                        <option value="xbox">Xbox</option>
                        <option value="playstation">PlayStation</option>
                        <option value="nintendo">Nintendo</option>
                    </select>
                </div>

                <div class="cuadricula_juegos">

                    <?php
                    // Helper para convertir textos a slugs
                    function slugify($texto) {
                        $texto = strtolower($texto);
                        $reemplazos = [
                            'á' => 'a','é' => 'e','í' => 'i','ó' => 'o','ú' => 'u','ñ' => 'n'
                        ];
                        $texto = strtr($texto, $reemplazos);
                        return preg_replace('/[^a-z0-9]+/','',$texto);
                    }
                    ?>

                    <?php if (!empty($catalogo_juegos)): ?>
                        <?php foreach ($catalogo_juegos as $juego): 
                            $categoria_slug  = slugify($juego['categoria']);
                            $plataforma_slug = slugify($juego['plataforma']);
                        ?>
                            <article class="targeta_jugo"
                                    data-categoria="<?php echo $categoria_slug; ?>"
                                    data-plataforma="<?php echo $plataforma_slug; ?>"
                                    data-precio="<?php echo $juego['precio']; ?>">

                                <img src="<?php echo htmlspecialchars($juego['imagen']); ?>" 
                                    alt="Portada de juego <?php echo htmlspecialchars($juego['nombre']); ?>">

                                <h3><?php echo htmlspecialchars($juego['nombre']); ?></h3>
                                <p><?php echo htmlspecialchars($juego['sinopsis']); ?></p>

                                <small style="color:#00d9ff; text-align:center; display:block;">
                                    <?php echo htmlspecialchars($juego['plataforma']." | ".$juego['categoria']); ?>
                                </small>

                                <?php 
                                    $precio_final = $juego['precio'];
                                    if ($juego['oferta'] > 0) {
                                        $descuento = $juego['precio'] * ($juego['oferta'] / 100);
                                        $precio_final = $juego['precio'] - $descuento;

                                        echo '<div class="precio">
                                                <s style="color:#999;">$'.number_format($juego['precio'],2).'</s>
                                                $'.number_format($precio_final,2).'
                                            </div>';
                                    } else {
                                        echo '<div class="precio">$'.number_format($precio_final,2).'</div>';
                                    }
                                ?>

                                <button class="boton_compra">Agregar al carrito</button>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay juegos disponibles actualmente.</p>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    </main>

    <!--Pie de pagina-->
    <footer>
        <div class="contenedor">
            <div class="espacio_footer">
                <!--General-->
                <div class="seccion_footer">
                    <h3>G&A</h3>
                    <p> Tu plataforma de videojuegos de confianza </p>
                </div>

                <!--Enlaces rapidos-->
                <div class="seccion_footer">
                    <h4> Descurbir </h4>
                    <ul>
                        <li><a href="../index.html"> Inicio </a></li>
                        <li><a href = "./catalogo.php"> Catalogo </a></li>
                        <li><a href="./oferta.php"> Ofertas </a></li>
                        <li><a href="./ubicacion.html"> Ubicación </a></li>
                        <li><a href="./carrito.html"> Carrito </a></li>
                    </ul>
                </div>

                <!--Soporte-->
                <div class="seccion_footer">
                    <h4> Soporte </h4>
                    <ul>
                        <li><a href="./soporte.html"> Soporte </a></li>
                        <li><a href="./cuenta.php"> Cuenta </a></li>
                    </ul>
                </div>

                <!--Legal-->
                <div class="seccion_footer">
                    <h4> Valores </h4>
                    <ul>
                        <li><a href="./terminos.html"> Términos y condiciones </a></li>
                        <li><a href="./privacidad.html"> Política de privacidad </a></li>
                    </ul>
                </div>

                <!--Redes sociales-->
                <div class="seccion_footer">
                    <h4> Siguenos </h4>
                    <div class="link_social">
                        <ul>
                            <li><a href="" target="_blank"> <img src="../Imagenes/Redes Sociales/facebook.jpg" alt="Facebook"> </a></li>
                            <li><a href="" target="_blank"> <img src="../Imagenes/Redes Sociales/x.jpg" alt="X"> </a></li>
                            <li><a href="" target="_blank"> <img src="../Imagenes/Redes Sociales/instagram.jpg" alt="Instagram"> </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Derechos reservados -->
            <div class="derechos">
                <p> &copy; 2025 G&A. Todos los derechos reservados </p>
            </div>
        </div>
    </footer>

    <!-- Archivo JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const filtroCategoria   = document.getElementById('filtro_categoria');
        const filtroPrecio      = document.getElementById('filtro_precio');
        const filtroPlataforma  = document.getElementById('filtro_plataforma');

        const tarjetas = document.querySelectorAll('.catalogo .cuadricula_juegos .targeta_jugo');

        function aplicarFiltros() {
            const categoriaSeleccionada  = filtroCategoria.value;   // ej. "accion"
            const precioSeleccionado     = filtroPrecio.value;      // ej. "0-200"
            const plataformaSeleccionada = filtroPlataforma.value;  // ej. "pc"

            tarjetas.forEach(function (card) {
                const categoria  = card.dataset.categoria;   // ej. "accion"
                const plataforma = card.dataset.plataforma;  // ej. "pc"
                const precio     = parseFloat(card.dataset.precio || 0);

                let mostrar = true;

                // Filtro por categoría
                if (categoriaSeleccionada !== 'todas' && categoria !== categoriaSeleccionada) {
                    mostrar = false;
                }

                // Filtro por plataforma
                if (mostrar && plataformaSeleccionada !== 'todos' && plataforma !== plataformaSeleccionada) {
                    mostrar = false;
                }

                // Filtro por precio
                if (mostrar && precioSeleccionado !== 'todos') {
                    switch (precioSeleccionado) {
                        case '0-200':
                            if (!(precio >= 0 && precio <= 200)) mostrar = false;
                            break;
                        case '200-600':
                            if (!(precio > 200 && precio <= 600)) mostrar = false;
                            break;
                        case '600-1000':
                            if (!(precio > 600 && precio <= 1000)) mostrar = false;
                            break;
                        case '1000-1500':
                            if (!(precio > 1000 && precio <= 1500)) mostrar = false;
                            break;
                        case '1500-2000':
                            if (!(precio > 1500 && precio <= 2000)) mostrar = false;
                            break;
                        case '2000+':
                            if (!(precio > 2000)) mostrar = false;
                            break;
                    }
                }

                card.style.display = mostrar ? '' : 'none';
            });
        }

        // Disparar filtros cuando cambie cualquier select
        filtroCategoria.addEventListener('change', aplicarFiltros);
        filtroPrecio.addEventListener('change', aplicarFiltros);
        filtroPlataforma.addEventListener('change', aplicarFiltros);
    });
    </script>

    <script src="../JavaScript/carrito.js"></script>
</body>
</html>
