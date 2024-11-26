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
            // Redirigir al administrador para ver los cambios
            header('Location: admin.php');
            exit();
        } catch (Exception $e) {
            echo "Error al agregar el jugador: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}

// Obtener la lista de jugadores para editar o eliminar
$jugadores = $database->select("tb_jugadores", ["id_jugador", "nombre_jugador", "puntaje"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
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

    <main class="main-content">
        <h2 class="page-title">Panel Admin: Agregar, Editar y Eliminar Jugadores</h2>

        <!-- Formulario para agregar jugador -->
        <div class="add-player-container">
            <h3>Agregar Jugador</h3>
            <form action="admin.php" method="POST" class="form-player">
                <label for="nombre_jugador">Nombre del Jugador:</label>
                <input type="text" id="nombre_jugador" name="nombre_jugador" required class="input-field">

                <label for="puntaje">Puntaje:</label>
                <input type="number" id="puntaje" name="puntaje" required class="input-field">

                <button type="submit" class="btn-add-player">Agregar</button>
            </form>
        </div>

        <!-- Lista de jugadores -->
        <h3 class="page-subtitle">Lista de Jugadores</h3>
        <table class="top10-table">
            <thead>
                <tr>
                    <th>Nombre del Jugador</th>
                    <th>Puntaje</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($jugadores) > 0): ?>
                    <?php foreach ($jugadores as $jugador): ?>
                        <tr>
                            <td><?= htmlspecialchars($jugador['nombre_jugador']) ?></td>
                            <td><?= $jugador['puntaje'] ?></td>
                            <td>
                                <a href="editar.php?id=<?= $jugador['id_jugador'] ?>" class="btn-edit">Editar</a>
                                <a href="eliminar.php?id=<?= $jugador['id_jugador'] ?>" class="btn-delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este jugador?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay jugadores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
