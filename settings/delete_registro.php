<?php
session_start();
include('conf_bd.php');
include('./conf_server.php');
//Conexão com o banco de dados
$conn = conexao_banco();
verificar_sessao();
$dados_user = dados_user();
//coleta id do usuario da session
$id_user = $dados_user['id'];

//coleta data selecionada pelo usuario
$data = $_POST['data'] ?? null;

if (!$data) {
    header('Location: ../home.php');
    exit;
}

//Criando query e realizando remoção do banco

$query = $conn->prepare("DELETE FROM ponto_diario WHERE usuario_id = ? AND data_completo = ?");
$query->bind_param("is", $id_user, $data);
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
