<?php
include("conf_server.php");
include("conf_bd.php");
$conn = conexao_banco();
session_start();
verificar_sessao();
$dados_user = dados_user();
//Configuração de data

$mes = $_POST["mes_pdf"];
$ano = $_POST["ano_pdf"];

if ($mes === ""|| $ano === "") {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Ocorreu um erro ao tenta gerar o PDF <br>Selecione o mes e ano para o PDF ser gerado!";
    header("location: ../registrar-ponto.php");
    exit;
}
var_dump($mes);
var_dump($ano);

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/pdf.css">
    <title>PDF</title>
</head>

<body>
    <section class="pagina-pdf">
        <div class="container-pdf">
            <div class="cabecalho-PDF">
                <h1>Folha de Ponto </h1>
            </div>

        </div>
    </section>

    <script></script>
</body>

</html>