<?php 

include('conf_bd.php');
include('../snippets/script.html');
//Conexão com o banco de dados
$conn = conexao_banco();
session_start();

//Coleta dados do usuario de cadastro
$nome = $_REQUEST['nome_completo'];
$username = $_REQUEST['username'];
$email = $_REQUEST['email'];
$setor = $_REQUEST['setor'];
$cpf = $_REQUEST['cpf'];
$senha = $_REQUEST['senha'];
//utilizanod o ternario para definir ou como false ou true a checkbox
$permissao = isset($_REQUEST['permissao']) ? "administrador" : "usuario" ;

//Valida caso o cpf Já exista no banco de dados

$cpf_valid = $conn->prepare("SELECT cpf FROM usuario");
$cpf_valid->execute();
$result = $cpf_valid->get_result();
//utilizar fetch_all para mais de uma informação em um array
$result_cpf = $result->fetch_all(MYSQLI_NUM);
var_dump($result_cpf);

//Verifica se dentro do array de cpf possui o digitado
for ($i=0; $i < count($result_cpf); $i++) { 
    //informa qual linha [$i] e qual coluna [0]
        if($result_cpf[$i][0] === $cpf){
            $_SESSION['cadastro'] = "usuario ja cadastrado";
            header("location: ../adicionar_user.php");
            exit;

        }
    }

$cadastro_user = $conn->prepare("INSERT INTO usuario(nome,cpf,,username,setor,email,senha,permissoes) VALUE (?,?,?,?,?,?,?)");

$cadastro_user->bind_param("sssssss", $nome,$cpf,$username,$setor,$email,$senha,$permissao);

if($cadastro_user->execute()){

    $_SESSION['cadastro'] = "cadastrado";
    header("location: ../adicionar_user.php");
    
}else{
    $_SESSION['cadastro'] = "falha";
    header("location: ../adicionar_user.php");   
} 


?>