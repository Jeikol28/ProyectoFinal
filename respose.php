<?php
require 'config.php';  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos del formulario no estén vacíos
    if (isset($_POST['nombre_usuario'], $_POST['contraseña'], $_POST['correo'])) {
        $nombre_usuario = trim($_POST['nombre_usuario']);
        $contraseña = trim($_POST['contraseña']);  
        $correo = trim($_POST['correo']);

        if (!empty($nombre_usuario) && !empty($contraseña) && !empty($correo)) {
            // Verificar si el nombre de usuario ya existe
            try {
                $usuarioExistente = $database->get("tb_usuarios", "nombre_usuario", [
                    "nombre_usuario" => $nombre_usuario
                ]);

                if ($usuarioExistente) {
                    echo "El nombre de usuario ya está en uso. Por favor, elige otro.";
                } else {
                    // Hashear la contraseña
                    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT, ['cost' => 10]);

                    // Insertar usuario en la base de datos
                    $database->insert("tb_usuarios", [
                        "nombre_usuario" => $nombre_usuario,
                        "contraseña" => $contraseña_hash, 
                        "correo" => $correo
                    ]);

                    header('Location: login.html');
                    exit(); 
                }
            } catch (Exception $e) {
                echo "Error al registrar el usuario: " . $e->getMessage();
            }
        } else {
            echo "Todos los campos son obligatorios.";
        }
    } else {
        echo "Faltan datos en el formulario.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <link rel="stylesheet" href="./css/main.css">  
</head>
<body>
    <h1>Usuario agregado correctamente, redirigiendo al login...</h1>
    <p>Si no eres redirigido automáticamente, <a href="login.html">haz clic aquí</a>.</p>
</body>
</html>
