<?php
session_start();

/* Eliminar todas las variables de sesión */
session_unset();

/* Destruir la sesión */
session_destroy();

/* Redirigir de vuelta a la página de cuenta (login) */
header("Location: ./Paginas/cuenta.php");
exit;
