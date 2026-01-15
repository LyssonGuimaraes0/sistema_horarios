<?php
session_start();
include('./conf_bd.php');
include('./conf_server.php');
$conn = conexao_banco();

verificar_sessao();
$dados_user = dados_user();
//Configuração de data

$mes = $_POST["mes"];
$ano = $_POST["ano"];


if (
    $mes === "" ||
    $ano === "" ||
    !isset($_FILES['anexo_mes']) ||
    $_FILES['anexo_mes']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Erro no envio do arquivo.";
    header("location: ../anexar_frequencia.php");
    exit;
}

/* ================= VALIDA PDF ================= */
$anexo = $_FILES['anexo_mes']['name'];
$verificar_anexo = pathinfo($anexo);

if (strtolower($verificar_anexo['extension']) !== 'pdf') {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Tipo de arquivo não suportado.<br> Envie um PDF.";
    header("location: ../anexar_frequencia.php");
    exit;
}

/*================ CRIAR CAMINHO E ARMAZENAR ARQUIVO NO SERVIDOR ==================*/

$baseDocs = __DIR__ . '/../docs';

$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

$nome = str_replace(" ", "_", $dados_user['nome']);
$nome_formatado = strtolower($nome);

$pdf_nome = "folha_ponto_id_{$dados_user['id']}_{$nome_formatado}" . "_" . $mes . "_" . $ano . ".pdf";

$baseDocs = __DIR__ . '/../docs';

$diretorio = $baseDocs
    . DIRECTORY_SEPARATOR . "id_{$dados_user['id']}_{$nome_formatado}"
    . DIRECTORY_SEPARATOR . "ponto_mensal"
    . DIRECTORY_SEPARATOR;


if (!is_dir($diretorio)) {
    mkdir($diretorio, 0777, true);
}

$caminho = $diretorio . $pdf_nome;

if ($registros_folha_ponto = folha_ponto_registro($dados_user['id'], $mes, $ano)) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Folha de ponto já Registrada.<br> Caso precise altera apague o registro anterior.";
    header("location: ../anexar_frequencia.php");
    exit;
}

if (file_exists($caminho)) {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Este arquivo já foi enviado anteriormente.";
    header("location: ../anexar_frequencia.php");
    exit;
}

if (!move_uploaded_file($_FILES['anexo_mes']['tmp_name'], $caminho)) {

    // fallback para Windows
    if (!copy($_FILES['anexo_mes']['tmp_name'], $caminho)) {
        die('FALHA TOTAL AO SALVAR ARQUIVO');
    }

    unlink($_FILES['anexo_mes']['tmp_name']);

    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Não foi possivel salvar o anexo.<br> Tente novamente!";
    header("location: ../anexar_frequencia.php");
    exit;
}

/* ============================ PREPARAR ENVIO PARA O BANCO DE DADOS ======================*/

$query = $conn->prepare('INSERT INTO folha_ponto_mensal (usuario_id,mes,ano,caminho_folha_de_ponto) VALUE (?,?,?,?)');
$query->bind_param("isss", $dados_user['id'], $mes, $ano, $caminho);
if (!$query->execute()) {
    $conn->close();
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Não foi possivel salvar o anexo.<br> Tente novamente!";
    header("location: ../anexar_frequencia.php");
    exit;
}
$conn->close();
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Folha Mensal adicionada com sucesso!";
header("location: ../anexar_frequencia.php");
exit;
