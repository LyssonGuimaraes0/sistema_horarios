<?php

include('./conf_bd.php');
include('./conf_server.php');

//Coleta dados do usuario
$dados_user = dados_user();


//Coleta dos horarios enviados pelo usuario

if (isset($_POST['entrada'])) {

$entrada = $_REQUEST['entrada'];
$saida_pf = $_REQUEST['saida_pf'];
$entrada_pf = $_REQUEST['entrada_pf'];
$saida = $_REQUEST['saida'];

//Coleta ID do usuario
$id_user = $dados_user['id'];

}




?>



