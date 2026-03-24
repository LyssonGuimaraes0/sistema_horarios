<?php


$bd_config = [
    'host' => '10.28.0.4',
    'username' => 'root',
    'password' => 'SenhaSegura!123',
    'database' => 'bd_banco_de_horas'
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
       throw new Exception("Falha na conexão");
    }
    return $conn;
}


?>