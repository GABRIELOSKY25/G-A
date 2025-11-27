<?php
session_start();
?>

<!--Version de HTML-->
<!DOCTYPE html>
<!--Inicio de la pagiona web-->
<html lang="es">

<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <!--Titutlo de Navegacion-->
    <title> Iniciar Sesion </title> 
    <!-- Logo de Navegacion -->
    <link rel = "icon" href = "../Logo_Principal.ico" type = "image/x-icon">
    <!-- Enlaces a hojas de estilo -->
    <link rel = "stylesheet" href = "../CSS/login.css">
    <script src = "https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<!--Contenido de la Pagina-->
<body>
    <!--Menu de navegacion-->
    <header>
        <!--Contenido-->
        <div class = "menu_navegacion">
            <div class = "contenedor">
                <div class = "logo">
                    <!--Ruta relativa-->
                    <a href = "../index.html">
                        <!--Ruta de imagen // src = ruta // alt = nombre // style = (width = ancho // height = alto)-->
                        <img src = "../Logo_Principal.png" alt = "G&A" style = "width: 60px; height: auto;">
                    </a>
                </div>

                <nav>
                    <!--Lista no ordenada-->
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
            <!--Seccion Inicio de Sesion-->
            <section class = "registro">
                <div class = "contenedor">
                    <div class = "registro_contenedor">
                        
                        <?php if (isset($_SESSION['correo'])): ?>
                            <!-- ===== VISTA CUANDO YA ESTÁ INICIADA LA SESIÓN ===== -->
                            <div class = "cuenta_nueva">
                                <h1> Hola, <?php echo htmlspecialchars($_SESSION['nombre']); ?> 👋 </h1>
                                <p> Has iniciado sesión con: <strong><?php echo htmlspecialchars($_SESSION['correo']); ?></strong> </p>

                                <p style="margin-top: 20px; text-align: justify;">
                                    Puedes seguir navegando por el catálogo, revisar tus juegos o regresar al inicio.
                                </p>

                                <!-- Botón para cerrar sesión -->
                                <form action="../cerrar_sesion.php" method="POST" 
                                    style="margin-top: 30px; display: flex; justify-content: center;">
                                    <button type="submit" class="boton_crear">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>

                            <!-- Beneficios (puedes dejarlo igual) -->
                            <div class = "beneficios">
                                <h2> Tu cuenta G&A </h2>
                                <ul>
                                    <li> 🎮 Accede a tus juegos y compras recientes </li>
                                    <li> 📦 Consulta tu historial de pedidos </li>
                                    <li> 🎯 Recomendaciones según tus gustos </li>
                                    <li> ⭐ Administra tus listas de deseos </li>
                                    <li> 🛡️ Soporte rápido y personalizado </li>
                                    <li> 🏆 Acceso a tus puntos y recompensas </li>
                                </ul>
                            </div>

                        <?php else: ?>
                            <!-- ===== VISTA CUANDO NO HAY SESIÓN (FORMULARIO) ===== -->

                            <!-- Contenedor principal de inicio de sesion -->
                            <div class = "cuenta_nueva">
                                <h1> Iniciar Sesión </h1>
                                <p> Accede a tu cuenta y continúa tu experiencia gamer </p>
                                
                                <form class = "formulario" 
                                    action = "../procesar_inicio_sesion.php" 
                                    method = "POST" 
                                    @submit.prevent="validarFormulario">

                                    <!--Correo-->
                                    <div class = "formulario_grupo">
                                        <label for = "email"> Correo Electrónico * </label>
                                        <input type = "email" id = "email" name = "email" 
                                            v-model = "formData.email"
                                            @input = "validarEmail"
                                            :class = "getFieldClass('email')"
                                            required 
                                            placeholder = "tu@email.com">
                                        <span class = "error-message" v-if = "errores.email">{{ errores.email }}</span>
                                    </div>

                                    <!--Contraseña-->
                                    <div class = "formulario_grupo">
                                        <label for = "password"> Contraseña * </label>
                                        <input type = "password" id = "password" name = "password"
                                            v-model = "formData.password"
                                            @input = "validarPassword"
                                            :class = "getFieldClass('password')"
                                            required 
                                            placeholder = "Ingresa tu contraseña">
                                        <span class = "error-message" v-if = "errores.password">{{ errores.password }}</span>
                                    </div>

                                    <!-- Botón principal de inicio de sesión -->
                                    <button type = "submit" class = "boton_crear" :disabled = "!formularioValido">
                                        Iniciar Sesión
                                    </button>

                                    <!-- Enlace a registro -->
                                    <p class = "texto_secundario">
                                        ¿No tienes cuenta?
                                        <a href = "login.html" class = "link"> Crear cuenta </a>
                                    </p>
                                </form>
                            </div>
                            
                            <!-- Beneficios de iniciar sesion -->
                            <div class = "beneficios">
                                <h2> Ventajas de iniciar sesión </h2>
                                <ul>
                                    <li> 🎮 Accede a tus juegos y compras recientes </li>
                                    <li> 📦 Consulta tu historial de pedidos </li>
                                    <li> 🎯 Recomendaciones según tus gustos </li>
                                    <li> ⭐ Administra tus listas de deseos </li>
                                    <li> 🛡️ Soporte rápido y personalizado </li>
                                    <li> 🏆 Acceso a tus puntos y recompensas </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </section>
        </main>


    <footer>
        <div class = "contenedor">
            <div class = "espacio_footer">
                <!--General-->
                <div class = "seccion_footer">
                    <h3>G&A</h3>
                    <p> Tu plataforma de videojuegos de confianza </p>
                </div>

                <!--Enlaces rapidos-->
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

                <!--Soporte-->
                <div class = "seccion_footer">
                    <h4> Soporte </h4>
                    <ul>
                        <li><a href = "./soporte.php"> Soporte </a></li>    
                        <li><a href = "./cuenta.php"> Cuenta </a></li>
                    </ul>
                </div> 

                <!--Legal-->
                <div class = "seccion_footer">
                    <h4> Valores </h4>
                    <ul>
                        <li><a href = "./terminos.html"> Términos y condiciones </a></li>
                        <li><a href = "./privacidad.html"> Política de privacidad </a></li>
                    </ul>
                </div> 

                <!--Redes sociales-->
                <div class = "seccion_footer">
                    <h4> Siguenos </h4>
                    <div class = "link_social">
                        <ul>
                            <li> <a href = "" target = "_blank"> <img src = "../Imagenes/Redes Sociales/facebook.jpg" alt = "Facebook" > </a> </li>
                            <li> <a href = "" target = "_blank"> <img src = "../Imagenes/Redes Sociales/x.jpg" alt = "X"> </a> </li>
                            <li> <a href = "" target = "_blank"> <img src = "../Imagenes/Redes Sociales/instagram.jpg" alt = "Instagram"> </a> </li>
                        </ul>
                    </div>
                </div> 
            </div>
            
            <!-- Derechos reservados -->
            <div class = "derechos">
                <p> &copy; 2025 G&A. Todos los derechos reservados </p>
            </div>
        </div>
    </footer>

    <script>
        const { createApp } = Vue;

        createApp({
        data() {
            return {
                formData: {
                    email: '',
                    password: '',
                    recordar: false
                },
                errores: {
                    email: '',
                    password: ''
                }
            }
        },

        computed: {
            formularioValido() {
                return (
                    this.formData.email !== '' &&
                    this.formData.password !== '' &&
                    Object.values(this.errores).every(e => e === '')
                );
            }
        },

        methods: {
            getFieldClass(field) {
                if (this.errores[field]) {
                    return 'error';
                }
                if (this.formData[field] && !this.errores[field]) {
                    return 'valid';
                }
                return '';
            },

            validarEmail() {
                const r = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!this.formData.email.trim()) {
                    this.errores.email = 'El correo electrónico es obligatorio';
                } else if (!r.test(this.formData.email)) {
                    this.errores.email = 'Por favor ingresa un correo electrónico válido';
                } else {
                    this.errores.email = '';
                }
            },

            validarPassword() {
                if (!this.formData.password.trim()) {
                    this.errores.password = 'La contraseña es obligatoria';
                } else {
                    this.errores.password = '';
                }
            },

            validarFormulario(event) {
                this.validarEmail();
                this.validarPassword();

                if (this.formularioValido) {
                    // Si todo está bien, se envía al PHP
                    event.target.submit();
                }
            }
        }
    }).mount('main');

    </script>
    <!-- Archivo JavaScript -->
    <script src="../JavaScript/carrito.js"></script>
</body>
</html>