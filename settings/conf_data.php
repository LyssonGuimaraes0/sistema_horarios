<?php

include('./conf_bd.php');
include('./conf_server.php');
$conn = conexao_banco();
session_start();

//Coleta dados do usuario
$dados_user = dados_user();
//Coleta ID do usuario
$id_user = $dados_user['id'];

//Coleta Dias, mes e ano
$dias_mes = $_REQUEST['dias'];
$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];

//Permite que no for os array andem caso valor seja nulo!
$entradas    = $_POST['entrada'] ?? [];
$saidas_pf   = $_POST['saida_pf'] ?? [];
$entradas_pf = $_POST['entrada_pf'] ?? [];
$saidas      = $_POST['saida'] ?? [];

//For para percorre os Array e busca os valores de datas presentes nele
for ($dia = 1; $dia <= $dias_mes; $dia++) {
    $entrada     = $entradas[$dia]    ?? null;
    $saida_pf    = $saidas_pf[$dia]   ?? null;
    $entrada_pf  = $entradas_pf[$dia] ?? null;
    $saida       = $saidas[$dia]      ?? null;
    $status      = null;

    //Verifica se o valor de todas as datas são nulo(so envia se pelo menos uma data tiver sido registrada)
    if (empty($entrada) && empty($saida_pf) && empty($entrada_pf) && empty($saida)) {
        //Pula para o proximo dia Ignorando o dia atual
        continue;
        //Aplica estado de completo caso todos estiverem com valores!
    } elseif (!empty($entrada[$dia]) && !empty($saida_pf[$dia]) && !empty($entrada_pf) && !empty($saida)) {
        $status = "Completo";
    }

    //Ternario onde caso a alguma String estiver vazia entraga o valor null, caso não mantem valor original(evita erro de registro no banco)
    $$entrada     = ($entrada === '')     ? null : $entrada;
    $saida_pf    = ($saida_pf === '')    ? null : $saida_pf;
    $entrada_pf  = ($entrada_pf === '')  ? null : $entrada_pf;
    $saida       = ($saida === '')       ? null : $saida;


    //Formarta data para unica string
    $data_completa = DateTime::createFromFormat('d/m/Y', "$dia/$mes/$ano")
        ->format('Y-m-d');

    //Caso não esteja todos o horarios registrados, não registra o status de completo
    if ($status === null) {
        $query_horas = $conn->prepare('INSERT INTO ponto_diario(usuario_id,data_completo,entrada,saida_almoco,volta_almoco,saida) VALUE(?,?,?,?,?,?)');
        $query_horas->bind_param("isssss", $id_user, $data_completa, $entrada, $saida_pf, $entrada_pf, $saida);
    } else {
        $query_horas = $conn->prepare('INSERT INTO ponto_diario(usuario_id,data_completo,entrada,saida_almoco,volta_almoco,saida,status_dia) VALUE(?,?,?,?,?,?,?)');
        $query_horas->bind_param("issssss", $id_user, $data_completa, $entrada, $saida_pf, $entrada_pf, $saida, $status);
    }

    //Retorna para Página de Registro
        if (!$query_horas->execute()) {
        $query_horas->close();
        $conn->close();

        $_SESSION['cadastro'] = "falha";
        $_SESSION['mensagem'] = "Ocorreu um erro ao tentar registrar os horários.";
        header("location: ../registrar-ponto.php");
        exit;
    }
    $query_horas->close();
}

$conn->close();

//Reenvia para Página de inicio
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Horarios foram registrados com sucesso!";
header("location: ../registrar-ponto.php");
