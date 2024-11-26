<?php
require 'config.php';

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

    <!-- Botón para agregar un nuevo jugador y score -->
    <div class="add-player-container">
        <a href="agregar.php" class="btn-add-player">Agregar nuevo jugador y score</a>
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
