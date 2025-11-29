<?php

require_once "./src/Database.php"; 
// Ajusta la ruta si Database.php está en otra carpeta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir datos del formulario
    $nombre = trim($_POST['nombre_completo']);
    $telefono = trim($_POST['telefono']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $genero = trim($_POST['genero']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmar_password = trim($_POST['confirmar_password']);

    $errores = [];

    // 2. Validaciones
    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";

    if (empty($telefono) || strlen($telefono) !== 10 || !ctype_digit($telefono)) {
        $errores[] = "El teléfono debe tener 10 dígitos.";
    }

    if (empty($fecha_nacimiento)) {
        $errores[] = "La fecha de nacimiento es obligatoria.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }

    if (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    if ($password !== $confirmar_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // validar género
    $generos_validos = ["Hombre", "Mujer", "Otro", "Prefiero no decir"];
    if (!in_array($genero, $generos_validos)) {
        $errores[] = "Género no válido.";
    }

    // Mostrar errores si existen
    if (!empty($errores)) {
        echo "<h1>Errores encontrados:</h1><ul>";
        foreach ($errores as $e) echo "<li>$e</li>";
        echo "</ul>";
        exit;
    }

    // 3. Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 4. Usar conexión con la clase Database
    try {
        $pdo = Database::getConnection();

        // 5. Insertar usuario
        $sql = "INSERT INTO usuario 
                (correo, nombre, telefono, /*fecha_nacimiento,*/ genero, contrasena)
                VALUES 
                (:correo, :nombre, :telefono, /*:fecha_nacimiento,*/ :genero, :contrasena)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':correo' => $email,
            ':nombre' => $nombre,
            ':telefono' => $telefono,
            ':genero' => $genero,
            ':contrasena' => $passwordHash
        ]);

        header("Location: http://localhost/Proyecto/Paginas/cuenta.php");
        exit;

    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {
            echo "<h1>Error: este correo ya está registrado.</h1>";
        } else {
            echo "<h1>Error en la base de datos:</h1>";
            echo $e->getMessage();
        }
    }

} else {
    echo "Acceso no permitido";
}
?>
