<?php

session_start();

// Evitar acceso directo GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./Paginas/cuenta.php");
    exit;
}

require_once __DIR__ . "./src/Database.php"; 

/* 1. Recibir datos */
$correo   = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($correo === '' || $password === '') {
    header("Location: ./Paginas/cuenta.php?error=campos_vacios");
    exit;
}

try {
    /* 2. Obtener conexión desde Database.php */
    $pdo = Database::getConnection();

    /* 3. Buscar usuario por correo */
    $sql = "SELECT correo, nombre, contrasena 
            FROM usuario 
            WHERE correo = :correo
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':correo' => $correo]);

    if ($stmt->rowCount() === 1) {

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        /* 4. Verificar contraseña */
        if (password_verify($password, $usuario['contrasena'])) {

            // Guardar sesión
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];

            // 🔥 GUARDAR CORREO EN LOCALSTORAGE (clave indispensable para procesar_venta)
            echo "<script>
                localStorage.setItem('correo_usuario', '{$usuario['correo']}');
                window.location.href = './Paginas/cuenta.php';
            </script>";
            exit;

        } else {
            header("Location: ./Paginas/cuenta.php?error=contrasena");
            exit;
        }

    } else {
        header("Location: ./Paginas/cuenta.php?error=correo_no_existe");
        exit;
    }

} catch (PDOException $e) {
    echo '<h1>Error en la base de datos:</h1>';
    echo $e->getMessage();
    exit;
}
