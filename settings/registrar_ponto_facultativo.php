<?php
session_start();
include ('conf_bd.php');
include ('conf_server.php');
$conn = conexao_banco();

//Coleta data
$data  = $_POST['ponto-facultativo'];

//Formata para padrão brasileiro para armazenar no banco
$array_data = explode("-",$data);
$dia_mes = "$array_data[2]/$array_data[1]";
$ano = $array_data[0];

//Armazenamento no banco de dados

$query = $conn->prepare('');

?>