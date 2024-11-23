<?php
    require 'config.php';  // Incluye la configuración de la base de datos

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar que los campos del formulario no estén vacíos
        if (isset($_POST['nombre_usuario'], $_POST['contraseña'], $_POST['correo'])) {
            $nombre_usuario = trim($_POST['nombre_usuario']);
            $contraseña = trim($_POST['contraseña']);  // Usamos 'contraseña' en lugar de 'password'
            $correo = trim($_POST['correo']);

            if (!empty($nombre_usuario) && !empty($contraseña) && !empty($correo)) {
                // Hashear la contraseña
                $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT, ['cost' => 10]);

                // Intentar insertar los datos en la base de datos
                try {
                    // Insertar usuario en la base de datos
                    $database->insert("tb_usuarios", [
                        "nombre_usuario" => $nombre_usuario,
                        "contraseña" => $contraseña_hash,  // Usamos 'contraseña' en lugar de 'password'
                        "correo" => $correo
                    ]);

                    // Redirigir a la página de login después de registrar el usuario
                    header('Location: login.php');
                    exit();  // Detener la ejecución del script para evitar múltiples redirecciones

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
    <p>Si no eres redirigido automáticamente, <a href="login.php">haz clic aquí</a>.</p>
</body>
</html>
