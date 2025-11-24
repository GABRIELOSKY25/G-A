<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ./Paginas/cuenta.php");
    exit;
}

/* 1. Recibir datos */
$correo   = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($correo === '' || $password === '') {
    header("Location: ./Paginas/cuenta.php?error=campos_vacios");
    exit;
}

try {
    /* 2. Conexión a la BD */
    $pdo = new PDO(
        "mysql:host=localhost;dbname=g_a;charset=utf8",
        "root",
        "peresoso888"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* 3. Buscar usuario por correo */
    $sql = "SELECT correo, nombre, contrasena 
            FROM Usuario
            WHERE correo = :correo
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':correo' => $correo]);

    if ($stmt->rowCount() === 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        /* 4. Verificar contraseña */
        if (password_verify($password, $usuario['contrasena'])) {

            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['nombre'] = $usuario['nombre'];

            // Redirigir de vuelta a la página de cuenta con un "flag"
            header("Location: ./Paginas/cuenta.php");
            exit;
        }

        
        else {

            header("Location: ./Paginas/cuenta.php?error=contrasena");
            exit;
        }

    } else {
        header("Location: ./Paginas/cuenta.php?error=correo_no_existe");
        exit;
    }

} catch (PDOException $e) {
    echo "<h1>Error en la base de datos:</h1>";
    echo $e->getMessage();
    exit;
}

