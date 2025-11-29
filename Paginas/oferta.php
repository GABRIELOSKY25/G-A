<?php
// Conexión con la base de datos usando tu clase Database.php
require_once __DIR__ . "/../src/Database.php";

try {
    $pdo = Database::getConnection();
} catch (Exception $e) {
    die("Error al conectar: " . $e->getMessage());
}

// Consulta general de productos con oferta
$sql_ofertas = "
    SELECT 
        id_juego,
        nombre,
        imagen,
        precio,
        oferta,
        sinopsis
    FROM producto
    WHERE oferta > 0
    AND estado = 'Activo'
";

$stmt = $pdo->query($sql_ofertas);
$resultado_ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML ORIGINAL (NO SE MODIFICÓ NADA) -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oferta</title>
    <link rel="icon" href="../Logo_Principal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../CSS/oferta.css">
</head>

<body>

<header>
    <div class="menu_navegacion">
        <div class="contenedor">
            <div class="logo">
                <a href="../index.html">
                    <img src="../Logo_Principal.png" alt="G&A" style="width: 60px; height: auto;">
                </a>
            </div>

            <nav>
                <ul>
                    <li><a href="../index.html"> Inicio </a></li>
                    <li><a href="catalogo.php"> Catalogo </a></li>
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

    <!--Título-->
    <section class="titulo">
        <div class="contenedor">
            <h1>Ofertas exclusivas</h1>
        </div>
    </section>

    <!--Ofertas destacadas-->
    <section class="ofertas_destacadas">
        <div class="contenedor">
            <h2>Destacadas</h2>
            <div class="cuadricula_juegos">

                <?php if (!empty($resultado_ofertas)): ?>

                    <?php foreach ($resultado_ofertas as $fila): ?>

                        <?php
                            $precio_original = $fila["precio"];
                            $porcentaje      = $fila["oferta"];
                            $precio_final    = $precio_original - ($precio_original * $porcentaje / 100);
                        ?>

                        <article class="targeta_jugo">
                            <div class="descuento">
                                -<?= $porcentaje ?>%
                            </div>

                            <img 
                                src="<?= $fila['imagen'] ?>" 
                                alt="Portada de <?= htmlspecialchars($fila['nombre']) ?>"
                            >

                            <h3><?= htmlspecialchars($fila["nombre"]) ?></h3>

                            <p><?= htmlspecialchars($fila["sinopsis"]) ?></p>

                            <div>
                                <span class="precio_normal">
                                    $<?= number_format($precio_original, 2) ?>
                                </span>

                                <span class="precio_descuento">
                                    $<?= number_format($precio_final, 2) ?>
                                </span>
                            </div>

                            <button 
                                class="boton_compra"
                                data-id="<?= $fila["id_juego"] ?>"
                            >
                                Agregar al carrito
                            </button>
                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p>No hay ofertas disponibles.</p>

                <?php endif; ?>

            </div>
        </div>
    </section>

    <!-- Tabla ofertas -->
    <section class="ofertas_semanales">
        <div class="contenedor">
            <h2>Ofertas de la semana</h2>

            <div class="tabla">
                <table>
                    <thead>
                        <tr>
                            <th>Juego</th>
                            <th>Precio Original</th>
                            <th>Descuento</th>
                            <th>Precio Final</th>
                            <th>Compra</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($resultado_ofertas)): ?>

                            <?php foreach ($resultado_ofertas as $fila): ?>

                                <?php
                                    $precio_original = $fila["precio"];
                                    $porcentaje      = $fila["oferta"];
                                    $precio_final    = $precio_original - ($precio_original * $porcentaje / 100);
                                ?>

                                <tr>
                                    <td>
                                        <div class="infomacion_tabla">
                                            <img 
                                                src="<?= $fila['imagen'] ?>" 
                                                alt="Portada de <?= htmlspecialchars($fila['nombre']) ?>"
                                            >
                                            <span><?= htmlspecialchars($fila["nombre"]) ?></span>
                                        </div>
                                    </td>

                                    <td>$<?= number_format($precio_original, 2) ?></td>

                                    <td>-<?= $porcentaje ?>%</td>

                                    <td>$<?= number_format($precio_final, 2) ?></td>

                                    <td>
                                        <button 
                                            class="boton_tabla"
                                            data-id="<?= $fila["id_juego"] ?>"
                                        >
                                            Añadir al carrito
                                        </button>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="5">No hay ofertas esta semana.</td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>
        </div>
    </section>

</main>

<footer>
    <div class="contenedor">
        <div class="espacio_footer">

            <div class="seccion_footer">
                <h3>G&A</h3>
                <p>Tu plataforma de videojuegos de confianza</p>
            </div>

            <div class="seccion_footer">
                <h4>Descubrir</h4>
                <ul>
                    <li><a href="../index.html">Inicio</a></li>
                    <li><a href="./catalogo.php">Catalogo</a></li>
                    <li><a href="./oferta.php">Ofertas</a></li>
                    <li><a href="./ubicacion.html">Ubicación</a></li>
                    <li><a href="./carrito.html">Carrito</a></li>
                </ul>
            </div>

            <div class="seccion_footer">
                <h4>Soporte</h4>
                <ul>
                    <li><a href="./soporte.php">Soporte</a></li>
                    <li><a href="./cuenta.php">Cuenta</a></li>
                </ul>
            </div>

            <div class="seccion_footer">
                <h4>Valores</h4>
                <ul>
                    <li><a href="./terminos.html">Términos y condiciones</a></li>
                    <li><a href="./privacidad.html">Política de privacidad</a></li>
                </ul>
            </div>

            <div class="seccion_footer">
                <h4>Síguenos</h4>
                <div class="link_social">
                    <ul>
                        <li><a href="#"><img src="../Imagenes/Redes Sociales/facebook.jpg" alt="Facebook"></a></li>
                        <li><a href="#"><img src="../Imagenes/Redes Sociales/x.jpg" alt="X"></a></li>
                        <li><a href="#"><img src="../Imagenes/Redes Sociales/instagram.jpg" alt="Instagram"></a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="derechos">
            <p>&copy; 2025 G&A. Todos los derechos reservados</p>
        </div>
    </div>
</footer>

<script src="../JavaScript/carrito.js"></script>

</body>
</html>
