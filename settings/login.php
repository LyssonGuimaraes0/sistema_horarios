<?php 
include('conf_bd.php');
//Conexão com o banco de dados
$conn = conexao_banco();
session_start();

$usuario = $_POST['usuario'];
$password = $_POST['password'];

//Verificação de login via Banco de dados

$veri_login = $conn->prepare("SELECT * FROM usuario WHERE username = ? AND senha = ?");

if(!$veri_login){
    die("Erro na preparação da consulta: " . $conn->error);
}else{

$veri_login->bind_param("ss", $usuario, $password);
$veri_login->execute();
$result = $veri_login->get_result();
$result_credencial = $result->fetch_assoc();
var_dump($result_credencial);

if($usuario === $result_credencial['username'] && $password === $result_credencial['senha']){
    $_SESSION['user_id'] = $result_credencial['id'];
    header("location: ../home.php");

} else {
    //Envia para a $_SESSION para apresenta uma tela de erro no java script
    $_SESSION['error_login'] = "falha";
    $_SESSION['mensagem'] = "Usuario ou senha errada<br>Tente Novamente";
    $conn->close();
    header("location: ../index.php");
    exit;
}

$conn->close();
}


?>

