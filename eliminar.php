<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id_jugador = $_GET['id'];

    try {
        // Eliminar el jugador de la base de datos
        $database->delete("tb_jugadores", [
            "id_jugador" => $id_jugador
        ]);

        // Redirigir al admin
        header('Location: admin.php');
        exit();
    } catch (Exception $e) {
        echo "Error al eliminar el jugador: " . $e->getMessage();
    }
} else {
    echo "ID de jugador no especificado.";
    exit();
}
