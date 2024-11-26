<?php
// Incluir la conexión a la base de datos
include('config.php');

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $puntaje = $_POST['puntaje'];

    // Insertar los datos en la base de datos
    $stmt = $pdo->prepare("INSERT INTO tb_jugadores (nombre_jugador, puntaje) VALUES (:nombre, :puntaje)");
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':puntaje', $puntaje);
    $stmt->execute();

    // Redirigir al archivo top10.php para mostrar los mejores puntajes
    header("Location: top10.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Puntaje</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="top10.php">Top 10</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <h2>Agregar Puntaje</h2>

    <!-- Formulario para agregar puntaje -->
    <form action="agregar.php" method="POST">
        <label for="nombre">Nombre del Jugador:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="puntaje">Puntaje:</label>
        <input type="number" id="puntaje" name="puntaje" required>

        <button type="submit">Agregar</button>
    </form>

</body>
</html>
