<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos del formulario no estén vacíos
    if (isset($_POST['nombre_jugador'], $_POST['puntaje'])) {
        $nombre_jugador = trim($_POST['nombre_jugador']);
        $puntaje = trim($_POST['puntaje']);

        if (!empty($nombre_jugador) && !empty($puntaje)) {
            try {
                // Insertar jugador y puntaje en la base de datos
                $database->insert("tb_jugadores", [
                    "nombre_jugador" => $nombre_jugador,
                    "puntaje" => $puntaje
                ]);

                // Redirigir a la página de Top 10 después de la inserción
                header('Location: top10.php');
                exit(); // Asegúrate de detener la ejecución después de redirigir
            } catch (Exception $e) {
                echo "Error al agregar el jugador: " . $e->getMessage();
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
    <title>Agregar Jugador</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <img src="img/logo.png" alt="Logo" class="logo">
        <nav class="navbar">
            <a href="index.php" class="nav-btn">Inicio</a>
            <a href="inicioJuego.html" class="nav-btn">Jugar</a>
            <a href="login.html" class="nav-btn">Login</a>
            <a href="logout.php" class="nav-btn">Logout</a>
            <a href="top10.php" class="nav-btn">Tabla</a>
        </nav>
    </header>

    <div class="add-player-container">
        <h2>Agregar Puntaje del Jugador</h2>

        <form action="agregar.php" method="POST">
            <label for="nombre_jugador">Nombre del Jugador:</label>
            <input type="text" id="nombre_jugador" name="nombre_jugador" required>

            <label for="puntaje">Puntaje:</label>
            <input type="number" id="puntaje" name="puntaje" required>

            <!-- Botón con la misma clase que en top10.php -->
            <button type="submit" class="btn-add-player">Agregar</button>
        </form>
    </div>
</body>
</html>