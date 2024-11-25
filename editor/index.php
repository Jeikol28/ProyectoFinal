
<?php
require '../config.php'; 

$configs = $database->select("tb_juego_config", "*");
   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego - Config</title>
</head>
<body>
    <h1>Registro Juego - Configs</h1>
    <a href="./agregar.php">Crear nuevo JSON</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Creado</th>
            <th>Actualizado</th>
            <th>Acciones</th>
        </tr>
        
    <?php
    foreach($configs as $config){
        echo "<tr>";
        echo "<td> JC-{$config['id_juego_config']}</td>";
        echo "<td>{$config['creado']}</td>";
        echo "<td>{$config['actualizado']}</td>";
        echo "<td><a href='./editar.php?id={$config['id_juego_config']}'>Editar</a> | <a href='./eliminar.php?id={$config['id_juego_config']}'>Eliminar</a> 
        | <a target= '_blank' href='./api.php?id={$config['id_juego_config']}'>Ver</a></td>";
        echo "</tr>";
    }

    ?>
    </table>
</body>
</html>