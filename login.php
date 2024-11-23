<?php
require 'config.php'; 
session_start();

// Inicializar mensaje de error como vacío
$error = '';

// Verifica si el formulario fue enviado mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos enviados desde el formulario
    $usuario = trim($_POST['nombre_usuario']);
    $contraseña = $_POST['contraseña'];

    // Verificar si los campos están vacíos
    if (!empty($usuario) && !empty($contraseña)) {
        // Buscar al usuario en la base de datos
        $usuarioDB = $database->select('tb_usuarios', '*', [
            'nombre_usuario' => $usuario
        ]);

        // Verificar si el usuario existe y la contraseña es válida
        if (count($usuarioDB) > 0 && password_verify($contraseña, $usuarioDB[0]['contraseña'])) {
            // Iniciar sesión
            $_SESSION['id_usuario'] = $usuarioDB[0]['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuarioDB[0]['nombre_usuario'];

            // Redirigir al usuario a la página principal
            header('Location: index.html');
            exit();
        } else {
            // Error: Usuario o contraseña incorrectos
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}

// Si hay un error, redirigir a login.html con el error como parámetro
if ($error) {
    header("Location: login.html?error=" . urlencode($error));
    exit();
}
?>