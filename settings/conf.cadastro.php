<?php 

include('conf_bd.php');
//Conexão com o banco de dados
$conn = conexao_banco();
session_start();

//Coleta dados do usuario de cadastro
$nome = $_REQUEST['nome_completo'];
$username = $_REQUEST['username'];
$email = $_REQUEST['email'];
$setor = $_REQUEST['setor'];
$senha = $_REQUEST['senha'];
//utilizanod o ternario para definir ou como false ou true a checkbox
/* $permissao = isset($_REQUEST['permissao']) ? "administrador" : "usuario" ;


$cadastro_user = $conn->prepare("INSERT INTO usuario(nome,username,setor,email,senha,permissoes) VALUE (?,?,?,?,?,?)");

$cadastro_user->bind_param("ssssss", $nome,$username,$setor,$email,$senha,$permissao);

if($cadastro_user->execute()){
    
} */









?>