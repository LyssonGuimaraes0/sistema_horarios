<?php 
include('conf_bd.php');
//Conexão com o banco de dados
$conn = conexao_banco();
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

var_dump($email);

$veri_login = $conn->prepare("SELECT * FROM user WHERE email = ? AND senha = ?");

if(!$veri_login){
    die("Erro na preparação da consulta: " . $conn->error);
}else{

$veri_login->bind_param("ss", $email, $password);
$veri_login->execute();
$result = $veri_login->get_result();
$result_credencial = $result->fetch_assoc();
var_dump($result_credencial);

if($email === $result_credencial['email'] && $password === $result_credencial['senha']){
    $_SESSION['user_id'] = $result_credencial['id'];
    echo "Login bem-sucedido!";
    header("location: ../home.php");

} else {
    echo "Falha no login. Verifique suas credenciais.";
}

$conn->close();
}


?>

