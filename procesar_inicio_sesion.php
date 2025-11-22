<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* 1. Recibir datos del formulario */
    $correo   = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /* 2. Validación básica */
    if ($correo === '' || $password === '') {
        header("Location: iniciar_sesion.html?error=campos_vacios");
        exit;
    }

    try {
        /* 3. Conexión a la BD */
        $pdo = new PDO("mysql:host=localhost;dbname=g_a;charset=utf8", "root", "peresoso888");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /* 4. Buscar usuario por correo */
        $sql = "SELECT correo, nombre, contrasena 
                FROM usuario 
                WHERE correo = :correo
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':correo' => $correo]);

        if ($stmt->rowCount() === 1) {

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            /* 5. Verificar contraseña */
            if (password_verify($password, $usuario['contrasena'])) {

                // 6. Guardar datos en sesión
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['nombre'] = $usuario['nombre'];

                // 7. Redirigir a la página principal o página de usuario
                header("Location: ../index.html");
                exit;

            } else {
                header("Location: iniciar_sesion.html?error=contrasena");
                exit;
            }

        } else {
            header("Location: iniciar_sesion.html?error=correo_no_existe");
            exit;
        }

    } catch (PDOException $e) {
        // Error inesperado
        header("Location: iniciar_sesion.html?error=server");
        exit;
    }

} else {
    header("Location: iniciar_sesion.html");
    exit;
}
?>
