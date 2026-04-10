<?php
session_start();
include('conf_bd.php');
include('conf_server.php');
//Conexão com o banco de dados
$conn = conexao_banco();
verificar_sessao();


//Coletar dados

$nome_feriado = $_POST['adicionar-nome-feriado'];
$data = $_POST['data-feriado'];

//quebra data ajusta para padrão do select

$array_data = explode('-',$data);
var_dump($array_data);
$dia_mes = "$array_data[2]/$array_data[1]";
$ano = $array_data[0];

//Verificar caso dados enviados já exista no banco

$query = $conn->prepare('SELECT feriado,dia_mes,ano FROM feriados WHERE dia_mes = ? AND ano = ?');
$query->bind_param('ss', $dia_mes, $ano);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();



if ($row) {
    $feriado_banco = $row['feriado'] ." - ".  $row['dia_mes'] . "/" . $row['ano'];
    $query->close();
    $conn->close();
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Data Já registrada!";
    $_SESSION['data-encontrada'] = $feriado_banco;
    header("location: ../feriado.php");
}



?>