<?php
    // Conexion con BD
    $servidor   = "localhost";
    $usuario    = "root";
    $password   = "peresoso888";
    $base_datos = "g_a";

    $conexion = new mysqli( $servidor , $usuario , $password , $base_datos );

    if( $conexion -> connect_error ){
        die( "Error de conexión: " . $conexion -> connect_error );
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
        FROM Producto
        WHERE oferta > 0
        AND estado = 'Activo'
    ";


    $resultado_ofertas = $conexion -> query( $sql_ofertas );
?>

<!--Version de HTML-->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <title> Oferta </title>
    <link rel = "icon" href = "../Logo_Principal.ico" type = "image/x-icon">
    <link rel = "stylesheet" href = "../CSS/oferta.css">
</head>

<body>

    <!--Menu de navegacion-->
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
                        <li><a href = "catalogo.php"> Catalogo </a></li>
                        <li><a href = "oferta.php"> Ofertas </a></li>
                        <li><a href = "ubicacion.html"> Ubicacion </a></li>
                        <li><a href = "carrito.html"> Carrito </a></li>
                        <li><a href = "cuenta.php"> Cuenta </a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main>

        <!--Titulo-->
        <section class = "titulo">
            <div class = "contenedor">
                <h1> Ofertas exclusivas </h1>
            </div>
        </section>

        <!--Ofertas destacadas-->
        <section class = "ofertas_destacadas">
            <div class = "contenedor">
                <h2> Destacadas </h2>
                <div class = "cuadricula_juegos">

                    <?php if( $resultado_ofertas && $resultado_ofertas -> num_rows > 0 ): ?>

                        <?php while( $fila = $resultado_ofertas -> fetch_assoc() ): ?>

                            <?php
                                $precio_original = $fila["precio"];
                                $porcentaje      = $fila["oferta"];
                                $precio_final    = $precio_original - ( $precio_original * $porcentaje / 100 );
                            ?>
                            <article class = "targeta_jugo">
                                <div class = "descuento">
                                    -<?php echo $porcentaje; ?>%
                                </div>

                                <img 
                                    src  = "<?php echo $fila['imagen']; ?>" 
                                    alt = "Portada de <?php echo htmlspecialchars($fila['nombre']); ?>"
                                >

                                <h3> <?php echo htmlspecialchars($fila["nombre"]); ?> </h3>

                                <p> <?php echo htmlspecialchars($fila["sinopsis"]); ?> </p>

                                <div>
                                    <span class = "precio_normal">
                                        $<?php echo number_format( $precio_original , 2 ); ?>
                                    </span>

                                    <span class = "precio_descuento">
                                        $<?php echo number_format( $precio_final , 2 ); ?>
                                    </span>
                                </div>

                                <button 
                                    class  = "boton_compra"
                                    data-id = "<?php echo $fila["id_juego"]; ?>"
                                >
                                    Agregar al carrito
                                </button>
                            </article>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <p> No hay ofertas disponibles. </p>

                    <?php endif; ?>

                </div>
            </div>
        </section>

        <!-- Tabla de ofertas semanales -->
        <section class = "ofertas_semanales">
            <div class = "contenedor">
                <h2> Ofertas de la semana </h2>

                <div class = "tabla">
                    <table>
                        <thead>
                            <tr>
                                <th> Juego </th>
                                <th> Precio Original </th>
                                <th> Descuento </th>
                                <th> Precio Final </th>
                                <th> Compra </th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                                // Reset cursor (volver a ejecutar consulta)
                                $resultado_ofertas = $conexion -> query( $sql_ofertas );

                                if( $resultado_ofertas && $resultado_ofertas -> num_rows > 0 ):
                            ?>

                                <?php while( $fila = $resultado_ofertas -> fetch_assoc() ): ?>

                                    <?php
                                        $precio_original = $fila["precio"];
                                        $porcentaje      = $fila["oferta"];
                                        $precio_final    = $precio_original - ( $precio_original * $porcentaje / 100 );
                                    ?>

                                    <tr>
                                        <td>
                                            <div class = "infomacion_tabla">
                                                <img 
                                                    src  = "<?php echo $fila['imagen']; ?>" 
                                                    alt = "Portada de juego <?php echo htmlspecialchars($fila['nombre']); ?>"
                                                >
                                                <span> <?php echo htmlspecialchars($fila["nombre"]); ?> </span>
                                            </div>
                                        </td>

                                        <td>
                                            $<?php echo number_format( $precio_original , 2 ); ?>
                                        </td>

                                        <td>
                                            -<?php echo $porcentaje; ?>%
                                        </td>

                                        <td>
                                            $<?php echo number_format( $precio_final , 2 ); ?>
                                        </td>

                                        <td>
                                            <button 
                                                class  = "boton_tabla"
                                                data-id = "<?php echo $fila["id_juego"]; ?>"
                                            >
                                                Añadir al carrito
                                            </button>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan = "5"> No hay ofertas esta semana. </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <div class = "contenedor">
            <div class = "espacio_footer">

                <div class = "seccion_footer">
                    <h3>G&A</h3>
                    <p> Tu plataforma de videojuegos de confianza </p>
                </div>

                <div class = "seccion_footer">
                    <h4> Descurbir </h4>
                    <ul>
                        <li><a href = "../index.html"> Inicio </a></li>
                        <li><a href = "./catalogo.php"> Catalogo </a></li>
                        <li><a href = "./oferta.php"> Ofertas </a></li>
                        <li><a href = "./ubicacion.html"> Ubicación </a></li>
                        <li><a href = "./carrito.html"> Carrito </a></li>
                    </ul>
                </div>

                <div class = "seccion_footer">
                    <h4> Soporte </h4>
                    <ul>
                        <li><a href = "./soporte.html"> Soporte </a></li>
                        <li><a href = "./cuenta.php"> Cuenta </a></li>
                    </ul>
                </div>

                <div class = "seccion_footer">
                    <h4> Valores </h4>
                    <ul>
                        <li><a href = "./terminos.html"> Términos y condiciones </a></li>
                        <li><a href = "./privacidad.html"> Política de privacidad </a></li>
                    </ul>
                </div>

                <div class = "seccion_footer">
                    <h4> Siguenos </h4>
                    <div class = "link_social">
                        <ul>
                            <li><a href=""><img src="../Imagenes/Redes Sociales/facebook.jpg" alt="Facebook"></a></li>
                            <li><a href=""><img src="../Imagenes/Redes Sociales/x.jpg" alt="X"></a></li>
                            <li><a href=""><img src="../Imagenes/Redes Sociales/instagram.jpg" alt="Instagram"></a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class = "derechos">
                <p> &copy; 2025 G&A. Todos los derechos reservados </p>
            </div>
        </div>
    </footer>

    <script src = "../JavaScript/carrito.js"></script>

</body>
</html>

<?php
    $conexion -> close();
?>
