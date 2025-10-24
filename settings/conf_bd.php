<?php

$bd_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'sistema_de_frequencia'
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

function dados_user()
{
    $conn = conexao_banco();
    $user_id = $_SESSION['user_id'];
    $consulta_user = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $consulta_user->bind_param("i", $user_id);
    $consulta_user->execute();
    $result_user = $consulta_user->get_result();
    $dados_user = $result_user->fetch_assoc();
    return $dados_user;
}


?>