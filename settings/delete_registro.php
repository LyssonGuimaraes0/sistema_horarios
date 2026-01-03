<?php
session_start();
include('conf_bd.php');
include('./conf_server.php');
//Conexão com o banco de dados
$conn = conexao_banco();
verificar_sessao();
$dados_user = dados_user();
//coleta id do usuario da session

//coleta data selecionada pelo usuario
$data = $_POST['data'] ?? null;

if (!$data) {
    header('Location: ../home.php');
    exit;
}
//verificar caso usuario tenha registrado como atestado

$query_status = $conn->prepare('SELECT status_dia FROM ponto_diario WHERE usuario_id = ? AND data_completo = ?');
$query_status->bind_param("is", $dados_user['id'], $data);
$query_status->execute();
$result = $query_status->get_result();
$status = $result->fetch_assoc();

if ($status['status_dia'] === "Atestado") {

    //Verifica se existem mais de uma data para o mesmo atestado
    $query_atestados = $conn->prepare('SELECT data_inicio FROM documento_justificativa WHERE usuario_id = ?');
    $query_atestados->bind_param("i", $dados_user['id']);
    $query_atestados->execute();
    $resultado = $query_atestados->get_result();
    while ($linha = $resultado->fetch_assoc()) {
        $data_banco = $linha['data_inicio'];

        if ($data_banco === $data) {
            continue;
        } else {

            $_SESSION['cadastro'] = "falha";
            $_SESSION['mensagem'] = "Horario vincualdo a um atestado <br> Remova o primerio dia de atestado adicionado!";
            header("location: ../registrar-ponto.php");
            exit;
        }
    }
}


//Criando query e realizando remoção do banco

$query = $conn->prepare("DELETE FROM ponto_diario WHERE usuario_id = ? AND data_completo = ?");
$query->bind_param("is", $dados_user['id'], $data);
if (!$query->execute()) {
    $query->close();
    $conn->close();

    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Ocorreu um erro tenta remover os horarios <br> Tente novamente mais tarde";
    header("location: ../registrar-ponto.php");
    exit;
}

$query->close();
$conn->close();

$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Horarios removidos com sucesso";
header("location: ../registrar-ponto.php");
exit;
