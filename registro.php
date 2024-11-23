<?php
    $title = "Registro de Jugadores";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Registro</title>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <div class="logo">
                <img src="img/logo.png" alt="Logo">
            </div>
            <h1>Crear una cuenta</h1>
            <form action="respose.php" method="POST">
                <label for="nombre_usuario">Nombre</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa tu nombre" required>

                <label for="correo">Email</label>
                <input type="email" id="correo" name="correo" placeholder="Ingresa tu email" required>

                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" placeholder="Ingresa tu contraseña" required>

                <button type="submit">Crear cuenta</button>
            </form>
            <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
        </div>
    </div>
</body>
</html>
