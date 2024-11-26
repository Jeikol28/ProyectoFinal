<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

require 'config.php';
date_default_timezone_set('America/Costa_Rica');

if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);

    // Imprimir los datos recibidos para depuración
    file_put_contents('php://stderr', print_r($data, true)); // Imprime en el log de errores

    if ($data && isset($data['longitud'], $data['navegador'], $data['pantalla'], $data['nivel'], $data['cerrado'])) {
        // Intentar insertar los datos en la base de datos.
        try {
            $database->insert("tb_seguimiento", [
                "longitud" => $data['longitud'],
                "tipo_dispositivo" => $data['navegador'],
                "tamaño_pantalla" => $data['pantalla'],
                "nivel" => $data['nivel'],
                "ha_cerrado_navegador" => $data['cerrado'],
                "fecha" => date('Y-m-d H:i:s')
            ]);
            echo json_encode(["status" => "success", "message" => "Tracking data saved"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Error al guardar los datos: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Datos incompletos o inválidos"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Se esperaba JSON en la solicitud"]);
}
?>

