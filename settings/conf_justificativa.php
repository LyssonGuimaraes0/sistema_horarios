<?php
session_start();
include('./conf_bd.php');
include('./conf_server.php');

$conn = conexao_banco();
$dados_user = dados_user();

/* ================= COLETA ================= */
$tipo_documento = $_POST['tipo_documento'] ?? '';
$data_registro  = $_POST['data'] ?? '';
$total_data     = (int)($_POST['dias_atestado'] ?? 0);

/* ================= VALIDAÇÕES ================= */
if (
    $tipo_documento === '' ||
    $data_registro === '' ||
    $total_data <= 0 ||
    !isset($_FILES['justi_pdf']) ||
    $_FILES['justi_pdf']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Preencha todos os campos e anexe o documento!";
    header("location: ../registrar-ponto.php");
    exit;
}

/* ================= VALIDA PDF ================= */
$anexo = $_FILES['justi_pdf']['name'];
$verificar_anexo = pathinfo($anexo);

if (strtolower($verificar_anexo['extension']) !== 'pdf') {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Tipo de arquivo não suportado. Envie um PDF.";
    header("location: ../registrar-ponto.php");
    exit;
}

/* ================= PREPARA CAMINHO ================= */
$novo_nome = "id_{$dados_user['id']}_Data_{$data_registro}.pdf";

$nome_formatado = strtolower(str_replace(" ", "_", $dados_user['nome']));
$diretorio = "../docs/id_{$dados_user['id']}_{$nome_formatado}/justificativas/";

if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

$caminho = $diretorio . $novo_nome;

/* ================= MOVE ARQUIVO ================= */
if (!move_uploaded_file($_FILES['justi_pdf']['tmp_name'], $caminho)) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Erro ao salvar o anexo.";
    header("location: ../registrar-ponto.php");
    exit;
}

/* ================= REGISTRA PONTO ================= */
$data_inicio = new DateTime($data_registro);
$data_atestado = clone $data_inicio;

$query_horas = $conn->prepare(
    "INSERT INTO ponto_diario 
    (usuario_id, data_completo, entrada, saida_almoco, volta_almoco, saida, status_dia)
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        entrada = IFNULL(VALUES(entrada), entrada),
        saida_almoco = IFNULL(VALUES(saida_almoco), saida_almoco),
        volta_almoco = IFNULL(VALUES(volta_almoco), volta_almoco),
        saida = IFNULL(VALUES(saida), saida),
        status_dia = VALUES(status_dia)"
);

$entrada      = "08:00";
$saida_almoco = "12:00";
$volta_almoco = "13:00";
$saida        = "17:00";
$status_dia   = "Atestado";

for ($i = 0; $i < $total_data; $i++) {

    $data_formatada = $data_atestado->format('Y-m-d');

    $dia_semana_nome = $data_atestado->format('l');

    if ($dia_semana_nome === "Saturday" || $dia_semana_nome === "Sunday") {
        $data_atestado->modify('+1 day');
        continue;
    }

    $query_horas->bind_param(
        "issssss",
        $dados_user['id'],
        $data_formatada,
        $entrada,
        $saida_almoco,
        $volta_almoco,
        $saida,
        $status_dia
    );

    if (!$query_horas->execute()) {
        $_SESSION['cadastro'] = "falha";
        $_SESSION['mensagem'] = "Erro ao registrar ponto.";
        header("location: ../registrar-ponto.php");
        exit;
    }

    $data_atestado->modify('+1 day');
}

/* ================= DATA FINAL ================= */
$data_fim = clone $data_inicio;
$data_fim->modify('+' . ($total_data - 1) . ' day');

/* ================= SALVA JUSTIFICATIVA ================= */
$query_atestados = $conn->prepare(
    "INSERT INTO documento_justificativa 
    (usuario_id, caminho_justificativa, descricao_motivo, data_inicio, data_fim)
    VALUES (?,?,?,?,?)"
);

$query_atestados->bind_param(
    "issss",
    $dados_user['id'],
    $caminho,
    $tipo_documento,
    $data_inicio->format('Y-m-d'),
    $data_fim->format('Y-m-d')
);

$query_atestados->execute();

$conn->close();

/* ================= SUCESSO ================= */
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Atestado registrado com sucesso!";
header("location: ../registrar-ponto.php");
exit;
