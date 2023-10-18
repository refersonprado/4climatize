<?php

    $dbHost = 'climatize.mysql.dbaas.com.br';
    $dbUsername = 'climatize';
    $dbPassword = 'Th3d00rs*@!Cli';
    $dbName = 'climatize';

    $connection = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    if($connection->connect_errno) {
        echo "Erro";
    }
?>