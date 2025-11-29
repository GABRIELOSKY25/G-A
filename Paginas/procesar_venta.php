<?php
header('Content-Type: application/json');
session_start();
require_once '../src/Database.php';

// Permitir POST JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

// Leer JSON desde el body
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'JSON inválido']);
    exit;
}

// Validar estructura
if (!isset($input['carrito'], $input['correo'], $input['total'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$carrito = $input['carrito'];
$correo  = $input['correo'];
$total   = $input['total'];

try {
    $pdo = Database::getConnection();
    $pdo->beginTransaction();

    // 1️⃣ INSERTAR VENTA
    $sqlVenta = "INSERT INTO Venta (correo, fecha, metodo_pago, pago, importe)
                 VALUES (:correo, CURDATE(), 'Tarjeta', :pago, :importe)";
    $stmtVenta = $pdo->prepare($sqlVenta);
    $stmtVenta->execute([
        ':correo' => $correo,
        ':pago'   => $total,
        ':importe'=> $total
    ]);

    $idVenta = $pdo->lastInsertId();

    // 2️⃣ Preparar Query para buscar ID del juego
    $sqlBuscar = "SELECT id_juego, stock FROM Producto WHERE nombre = :nombre LIMIT 1";
    $stmtBuscar = $pdo->prepare($sqlBuscar);

    // 3️⃣ Preparar INSERT detalle
    $sqlDet = "INSERT INTO Detalle_venta (id_venta, id_juego, precio_unitario, cantidad, subtotal)
               VALUES (:id_venta, :id_juego, :precio, :cantidad, :subtotal)";
    $stmtDet = $pdo->prepare($sqlDet);

    // 4️⃣ Preparar actualización de stock
    $sqlStock = "UPDATE Producto SET stock = stock - :cantidad 
                 WHERE id_juego = :id_juego AND stock >= :cantidad";
    $stmtStock = $pdo->prepare($sqlStock);

    // 5️⃣ Procesar cada producto del carrito
    foreach ($carrito as $item) {

        // Buscar ID por nombre
        $stmtBuscar->execute([':nombre' => $item['nombre']]);
        $juego = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

        if (!$juego) {
            throw new Exception("El producto '{$item['nombre']}' no existe.");
        }

        if ($juego['stock'] < $item['cantidad']) {
            throw new Exception("Stock insuficiente de '{$item['nombre']}'.");
        }

        // Insertar detalle venta
        $stmtDet->execute([
            ':id_venta' => $idVenta,
            ':id_juego' => $juego['id_juego'],
            ':precio'   => $item['precio'],
            ':cantidad' => $item['cantidad'],
            ':subtotal' => $item['precio'] * $item['cantidad']
        ]);

        // Actualizar stock
        $stmtStock->execute([
            ':cantidad' => $item['cantidad'],
            ':id_juego' => $juego['id_juego']
        ]);

        if ($stmtStock->rowCount() === 0) {
            throw new Exception("Error actualizando stock del juego ID: {$juego['id_juego']}");
        }
    }

    // Confirmar la transacción
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Venta procesada exitosamente',
        'id_venta' => $idVenta
    ]);
    exit;

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
