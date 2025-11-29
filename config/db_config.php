<?php
// config/db_config.php

define('DB_HOST', 'localhost'); 
define('DB_USER', 'root');      
define('DB_PASS', '');          // Contraseña vacía
define('DB_NAME', 'g_a');       // Asegúrate de que este sea el nombre de tu DB
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', '3308');      // ¡PUERTO CORREGIDO!

/**
 * Función para obtener la conexión a la base de datos (PDO)
 */
function getDBConnection() {
    // La cadena DSN ahora especifica el puerto
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        // Intentamos la conexión con el puerto especificado
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        // En caso de error, muestra el mensaje para depurar
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}