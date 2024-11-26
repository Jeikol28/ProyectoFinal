<?php
// Incluir la conexión a la base de datos
include('config.php');

// Obtener los mejores 10 puntajes
$stmt = $pdo->query("SELECT nombre_jugador, puntaje FROM tb_jugadores ORDER BY puntaje DESC LIMIT 10");
$topScores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Jugadores</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="agregar.php">Agregar Puntaje</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <h2>Top 10 Jugadores</h2>

    <!-- Tabla para mostrar los mejores 10 puntajes -->
    <table>
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

</body>
</html>