<?php 
/*include('conf_bd.php');
//Conexão com o banco de dados
$conn = conexao_banco();*/
session_start();

$usuario = $_POST['usuario'];
$password = $_POST['password'];

var_dump($usuario);

//Verificação de Login para teste

//ler um json para busca um valor
$login_json = json_decode(file_get_contents('login-config.json'), true);
// Percorre as contas do JSON


foreach ($login_json as $conta) {
    if ($usuario === $conta['usuario'] && $password === $conta['password']) {
        header("location: ../home.php");
        exit();
    } else {
        echo "Falha no login. Verifique suas credenciais.";
    }
}





//Verificação de login via Banco de dados
/*var_dump($email);

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
}*/


?>

