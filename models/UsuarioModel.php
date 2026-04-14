<?php

require_once  __DIR__ . '/../settings/conexao.php';

//Query para busca de Usuario

function buscar_usuario($usuario, $password)
{
    $conn = conexao_banco();

    $VerificarLogin = $conn->prepare("SELECT * FROM usuario WHERE username = ? AND senha = ?");
    $VerificarLogin->bind_param("ss", $usuario, $password);
    $VerificarLogin->execute();
    $result = $VerificarLogin->get_result();
    $RegistroUsuario = $result->fetch_assoc();

    $conn->close();

    return $RegistroUsuario;
}


//Coleta dados do usuario logado

function dados_user()
{

    $conn = conexao_banco();

    $usuario_id = $_SESSION['user_id'];

    $query = $conn->prepare("SELECT u.id, 
    u.nome,
    u.cpf,
    u.setor,
    u.cargo,
    u.permissoes,
    u.username,
    u.email
    FROM usuario u WHERE u.id = ?;");

    $query->bind_param("s", $usuario_id);

    $query->execute();
    $resultado = $query->get_result();
    $dados_usuario = $resultado->fetch_assoc();
    $conn->close();

    return $dados_usuario;
}



?>