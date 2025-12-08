<?php


$bd_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'banco_de_horas'
];

function conexao_banco()
{
    global $bd_config;
    $conn = new mysqli(
        $bd_config['host'],
        $bd_config['username'],
        $bd_config['password'],
        $bd_config['database']
    );
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }
    return $conn;
}


?>