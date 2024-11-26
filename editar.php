<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id_jugador = $_GET['id'];

    // Obtener los datos del jugador a editar
    $jugador = $database->get("tb_jugadores", "*", [
        "id_jugador" => $id_jugador
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar que los campos no estén vacíos
        $nombre_jugador = trim($_POST['nombre_jugador']);
        $puntaje = trim($_POST['puntaje']);

        if (!empty($nombre_jugador) && !empty($puntaje)) {
            try {
                // Actualizar los datos del jugador
                $database->update("tb_jugadores", [
                    "nombre_jugador" => $nombre_jugador,
                    "puntaje" => $puntaje
                ], [
                    "id_jugador" => $id_jugador
                ]);

                // Redirigir al admin
                header('Location: admin.php');
                exit();
            } catch (Exception $e) {
                echo "Error al actualizar el jugador: " . $e->getMessage();
            }
        } else {
            echo "Todos los campos son obligatorios.";
        }
    }
} else {
    echo "ID de jugador no especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jugador</title>
</head>
<body>
    <h2>Editar Jugador</h2>

    <form action="editar.php?id=<?= $jugador['id_jugador'] ?>" method="POST">
        <label for="nombre_jugador">Nombre del Jugador:</label>
        <input type="text" id="nombre_jugador" name="nombre_jugador" value="<?= htmlspecialchars($jugador['nombre_jugador']) ?>" required><br>

        <label for="puntaje">Puntaje:</label>
        <input type="number" id="puntaje" name="puntaje" value="<?= $jugador['puntaje'] ?>" required><br>

        <button type="submit">Actualizar</button>
    </form>

</body>
</html>
