<?php
define('AJAX', true);
ob_start();
session_start();

header('Content-Type: application/json; charset=utf-8');
session_start();
include('conf_bd.php');
include('conf_server.php');


try {
    $conn = conexao_banco();
    $dado_user = dados_user();
} catch (Throwable $e) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Erro interno.";

    echo json_encode([
        'status' => 'erro'
    ]);
    exit;
}


/**
 * Lê o JSON enviado pelo fetch
 */
$dados = json_decode(file_get_contents('php://input'), true);

if (!$dados) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Erro ao editar horário. Tente novamente.";

    ob_clean();
    echo json_encode([
        'status' => 'erro'
    ]);
    exit;
}

$data = $dados['data'] ?? null;
$dias = $dados['dias'] ?? null;

if (!$data || !is_array($dias) || empty($dias)) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Dados incompletos.";
    ob_clean();
    echo json_encode([
        'status' => 'erro'
    ]);
    exit;
}

/**
 * Pega o único dia enviado
 */
$dia = array_key_first($dias);
$horarios = $dias[$dia];

/**
 * Normaliza valores vazios
 */
function normalizar($v) {
    return ($v === '' || $v === null) ? null : $v;
}

$entrada       = normalizar($horarios['entrada'] ?? null);
$saida_almoco  = normalizar($horarios['saida_pf'] ?? null);
$volta_almoco  = normalizar($horarios['entrada_pf'] ?? null);
$saida         = normalizar($horarios['saida'] ?? null);

/**
 * Define status do dia
 */
$status = ($entrada && $saida_almoco && $volta_almoco && $saida)
    ? 'Completo'
    : 'Em Andamento';

/**
 * Atualiza no banco
 */
$query = $conn->prepare(
    'UPDATE ponto_diario 
     SET entrada = ?, 
         saida_almoco = ?, 
         volta_almoco = ?, 
         saida = ?, 
         status_dia = ?
     WHERE usuario_id = ? 
       AND data_completo = ?'
);

$query->bind_param(
    'sssssis',
    $entrada,
    $saida_almoco,
    $volta_almoco,
    $saida,
    $status,
    $dado_user['id'],
    $data
);

if (!$query->execute()) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Erro ao salvar horário.";
    ob_clean();
    echo json_encode([
        'status' => 'erro'
    ]);
    exit;
}

$conn->close();

/**
 * Sucesso
 */
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Horário atualizado com sucesso!";
ob_clean();
echo json_encode([
    'status'   => 'ok',
    'redirect' => 'registrar-ponto.php'
]);
exit;
