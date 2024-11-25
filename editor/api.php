
<?php
require '../config.php'; 

if($_GET){
    $data = $database->select("tb_juego_config", "*",[
        "id_juego_config" => $_GET['id']
    ]);
     $response = $data[0]['data_juego'];
    //echo $response;

    //decode json string to array
    $response = json_decode($response, true);
    
    //encode array to json whith pretty print
    echo json_encode($response, JSON_PRETTY_PRINT);
}
   
?>