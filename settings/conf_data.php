<?php
session_start();
include('./conf_bd.php');
include('./conf_server.php');
$conn = conexao_banco();

$_SESSION['mes_selecionado'] = $_POST['mes'] ?? null;
$_SESSION['ano_selecionado'] = $_POST['ano'] ?? null;


$horario_cargo = horario_cargo();

//Coleta dados do usuario
$dados_user = dados_user();
//Coleta ID do usuario
$id_user = $dados_user['id'];

//Coleta dia, mes e ano
$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];
$dia = $_POST['dia'];


//Permite que no for os array andem caso valor seja nulo!
$entrada    = $_POST['entrada'][$dia] ?? [];
$saida      = $_POST['saida'][$dia] ?? [];
$saida_pf   = $_POST['saida_pf'][$dia] ?? [];
$entrada_pf = $_POST['entrada_pf'][$dia] ?? [];
$status = null;

//Ternario onde caso a alguma String estiver vazia entraga o valor null, caso não mantem valor original(evita erro de registro no banco)
$entrada     = ($entrada === '')     ? null : $entrada;
$saida       = ($saida === '')       ? null : $saida;
$entrada_pf  = ($entrada_pf === '')  ? null : $entrada_pf;
$saida_pf    = ($saida_pf === '')    ? null : $saida_pf;


$data_completa = DateTime::createFromFormat('d/m/Y', "$dia/$mes/$ano")
    ->format('Y-m-d');

//Validação para caso o usuario for estagiario ou funcionario clt

//========================================CARGO ESTÁGIARIO========================================

if ($horario_cargo['cargo'] == "Estágiario-Manha" || $horario_cargo['cargo'] == "Estágiario-Tarde") {

    if (!empty($entrada) && !empty($saida)) {
        $status = "Completo";
    }

    //Caso não esteja todos o horarios registrados, não registra o status de completo
    if ($status === null) {
        $query_horas = $conn->prepare('INSERT INTO ponto_diario (usuario_id, data_completo, entrada, saida) VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            entrada = IFNULL(VALUES(entrada), entrada),
            saida = IFNULL(VALUES(saida), saida)');
        $query_horas->bind_param("isss", $id_user, $data_completa, $entrada, $saida);
    } else {
        $query_horas = $conn->prepare("INSERT INTO ponto_diario 
    (usuario_id, data_completo, entrada, saida, status_dia) 
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        entrada      = IFNULL(VALUES(entrada), entrada),
        saida        = IFNULL(VALUES(saida), saida),
        status_dia   = IF(status_dia = 'Atestado', status_dia, VALUES(status_dia))");
        $query_horas->bind_param("issss", $id_user, $data_completa, $entrada, $saida, $status);
    }
//===================================================================================================


//========================================CARGO NÃO ESTÁGIARIO========================================

} else {

    if (!empty($entrada) && !empty($saida_pf) && !empty($entrada_pf) && !empty($saida)) {
        $status  = "Completo";
    }

    //Caso não esteja todos o horarios registrados, não registra o status de completo
    if ($status === null) {
        $query_horas = $conn->prepare('INSERT INTO ponto_diario (usuario_id, data_completo, entrada, saida_almoco, volta_almoco, saida) VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            entrada = IFNULL(VALUES(entrada), entrada),
            saida_almoco = IFNULL(VALUES(saida_almoco), saida_almoco),
            volta_almoco = IFNULL(VALUES(volta_almoco), volta_almoco),
            saida = IFNULL(VALUES(saida), saida)');
        $query_horas->bind_param("isssss", $id_user, $data_completa, $entrada, $saida_pf, $entrada_pf, $saida);
    } else {
        $query_horas = $conn->prepare("INSERT INTO ponto_diario 
    (usuario_id, data_completo, entrada, saida_almoco, volta_almoco, saida, status_dia) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        entrada      = IFNULL(VALUES(entrada), entrada),
        saida_almoco = IFNULL(VALUES(saida_almoco), saida_almoco),
        volta_almoco = IFNULL(VALUES(volta_almoco), volta_almoco),
        saida        = IFNULL(VALUES(saida), saida),
        status_dia   = IF(status_dia = 'Atestado', status_dia, VALUES(status_dia))");
        $query_horas->bind_param("issssss", $id_user, $data_completa, $entrada, $saida_pf, $entrada_pf, $saida, $status);
    }
}

//=============================================================================================

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


$conn->close();

//Reenvia para Página de inicio
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Horarios foram registrados com sucesso!";
header("location: ../registrar-ponto.php");
