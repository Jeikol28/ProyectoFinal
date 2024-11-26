<?php
require 'config.php';

// Verificar si el formulario de agregar jugador fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_jugador'], $_POST['puntaje'])) {
    $nombre_jugador = trim($_POST['nombre_jugador']);
    $puntaje = trim($_POST['puntaje']);

    if (!empty($nombre_jugador) && !empty($puntaje)) {
        try {
            // Insertar el nuevo jugador
            $database->insert("tb_jugadores", [
                "nombre_jugador" => $nombre_jugador,
                "puntaje" => $puntaje
            ]);
            // Actualizar la lista de los mejores 10 puntajes
            header('Location: top10.php'); // Recargar la página para ver el nuevo jugador agregado
            exit();
        } catch (Exception $e) {
            echo "Error al agregar el jugador: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Obtener los mejores 10 puntajes
$sql = "SELECT nombre_jugador, puntaje FROM tb_jugadores ORDER BY puntaje DESC LIMIT 10";
$result = $database->query($sql);

// Verificar si la consulta fue exitosa y obtener los resultados
if ($result) {
    $topScores = $result->fetchAll(PDO::FETCH_ASSOC);
} else {
    $topScores = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Jugadores</title>
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
            <a href="#" class="nav-btn">Tabla</a>
        </nav>
    </header>

    <main class="main-content">
        <h2 class="page-title">Top 10 Jugadores</h2>

        <!-- Formulario para agregar jugador -->
        <div class="add-player-container">
            <h3>Agregar nuevo jugador y score</h3>
            <form action="top10.php" method="POST" class="form-player">
                <label for="nombre_jugador">Nombre del Jugador:</label>
                <input type="text" id="nombre_jugador" name="nombre_jugador" required class="input-field">

                <label for="puntaje">Puntaje:</label>
                <input type="number" id="puntaje" name="puntaje" required class="input-field">

                <button type="submit" class="btn-add-player">Agregar</button>
            </form>
        </div>

        <!-- Tabla para mostrar los mejores 10 puntajes -->
        <table class="top10-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jugador</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($topScores) > 0): ?>
                    <?php foreach ($topScores as $index => $score): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($score['nombre_jugador']) ?></td>
                            <td><?= $score['puntaje'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay puntajes registrados aún.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
