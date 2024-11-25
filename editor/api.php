
<?php
// Permiso pra acceder.
header("Access-Control-Allow-Origin: http://localhost:8080");

require '../config.php'; 

if($_GET){
    $data = $database->select("tb_juego_config", "*",[
        "id_juego_config" => $_GET['id']
    ]);

     $response = trim ($data[0]['data_juego']);
    //echo $response;

    //decode json string to array
    $response = json_decode($response, true);

    header('Content-Type: application/json');
    
    //encode array to json whith pretty print
    echo json_encode($response, JSON_PRETTY_PRINT);
}
   
?>