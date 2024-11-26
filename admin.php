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
</head>
<body>
    <header>
        <nav>
            <a href="index.php">Inicio</a>
        </nav>
    </header>

    <h2>Panel Admin: Agregar, Editar y Eliminar Jugadores</h2>

    <!-- Formulario para agregar jugador -->
    <h3>Agregar Jugador</h3>
    <form action="admin.php" method="POST">
        <label for="nombre_jugador">Nombre del Jugador:</label>
        <input type="text" id="nombre_jugador" name="nombre_jugador" required><br>

        <label for="puntaje">Puntaje:</label>
        <input type="number" id="puntaje" name="puntaje" required><br>

        <button type="submit">Agregar</button>
    </form>

    <h3>Lista de Jugadores</h3>
    <!-- Mostrar la lista de jugadores con opciones de editar y eliminar -->
    <table>
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
                            <a href="editar.php?id=<?= $jugador['id_jugador'] ?>">Editar</a> | 
                            <a href="eliminar.php?id=<?= $jugador['id_jugador'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este jugador?')">Eliminar</a>
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

</body>
</html>
