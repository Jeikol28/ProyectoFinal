<?php
    require 'config.php'; // Asegúrate de que este archivo contiene la configuración de la base de datos

    session_start();

    // Verifica si el formulario fue enviado
    if ($_POST) {
        // Obtener los valores del formulario
        $usuario = $_POST['nombre_usuario'];
        $contraseña = $_POST['contraseña'];

        // Buscar al usuario en la base de datos
        $usuarioDB = $database->select('tb_usuarios', '*', [
            'nombre_usuario' => $usuario
        ]);

        // Verificar si el usuario existe y la contraseña es correcta
        if (count($usuarioDB) > 0 && password_verify($contraseña, $usuarioDB[0]['contraseña'])) {
            // Usuario válido, iniciar sesión
            $_SESSION['id_usuario'] = $usuarioDB[0]['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuarioDB[0]['nombre_usuario'];
            header('Location: ./login.php'); 
            exit();
        } else {
            // Si el usuario o la contraseña son incorrectos, mostrar error
            $error = "Usuario o contraseña incorrectos.";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/main.css"> 
    <title>Iniciar sesión</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <img src="img/logo.png" alt="Logo">
            </div>
            <h1>Bienvenido de vuelta</h1>
            <!-- Formulario de inicio de sesión -->
            <form action="./login.php" method="POST">
                <label for="nombre_usuario">Nombre de usuario</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa tu nombre de usuario" required>

                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>

                <button type="submit">Iniciar sesión</button>
            </form>
            <p>¿Todavía no tienes una cuenta? <a href="registro.php">Crear una ahora</a></p>

            <!-- Mostrar error si existe -->
            <?php if (isset($error)): ?>
                <div class="error" style="color: red; text-align: center; margin-top: 10px;">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

