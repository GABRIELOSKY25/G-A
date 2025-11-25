<?php
require_once __DIR__ . '/config/db_config.php'; // Asegúrate de que la ruta sea esta

try {
    $pdo = getDBConnection();
    echo "<h1>¡Conexión a la base de datos exitosa!</h1>";
    
    // Ejecuta una consulta simple para verificar la tabla Producto
    $stmt = $pdo->query("SELECT COUNT(*) FROM Producto");
    $count = $stmt->fetchColumn();
    echo "<p>Hay **$count** juegos registrados en la tabla Producto.</p>";

} catch (Exception $e) {
    echo "⚠️ Falló la prueba de conexión o consulta:<br>";
    echo "Error: " . $e->getMessage();
}
?>