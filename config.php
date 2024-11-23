<?php 
    namespace Medoo;
    require 'Medoo.php';

    if(!isset($database)){
        /* 
        - For Laragon: username='root' / password=''
        - For MAMP: username='root' / password='root'
          */
        $database = new Medoo([
            'type'=>'mysql',
            'host' => 'localhost',
            'database' => 'proyecto_final',
            'username' => 'root',
            'password' => ''
        ]);
    }

?>