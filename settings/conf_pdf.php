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

if ($mes === "" || $ano === "") {
    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Ocorreu um erro ao tenta gerar o PDF <br>Selecione o mes e ano para o PDF ser gerado!";
    header("location: ../registrar-ponto.php");
    exit;
}

//Array de Meses

$meses = [
    "01"  => 'Janeiro',
    "02"  => 'Fevereiro',
    "03"  => 'Março',
    "04"  => 'Abril',
    "05"  => 'Maio',
    "06"  => 'Junho',
    "07"  => 'Julho',
    "08" => 'Agosto',
    "09" => 'Setembro',
    "10" => 'Outubro',
    "11" => 'Novembro',
    "12" => 'Dezembro'
];

$nome_mes = $meses[$mes];


//Coleta Dia Atual

$mes_atual = mese_atual();
$data_atual = $mes_atual['data_completa'];

?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/main.css">
    <title>PDF</title>
</head>

<body>
    <section class="pagina-pdf">
        <div class="container-pdf">
            <div class="cabecalho">
                <div class="cabecalho-titulo">
                    <h1>Folha de Ponto <?= "$nome_mes/$ano" ?></h1>
                </div>
                <div class="cabecalho-mes">
                    <div class="periodo">
                        <span>Período</span>
                    </div>
                    <div class="container-meses">
                        <div class="row-data">
                            <div class="dado texto">
                                <span>Data Inicio</span>
                            </div>
                            <div class="dado data-dado">
                                <span><?= "01/$mes/$ano" ?></span>
                            </div>
                        </div>

                        <div class="row-data">
                            <div class="dado texto">
                                <!--Pega data Final-->
                                <?php
                                $data = new dateTime("$ano-$mes-01");
                                $ultimoDia = $data->format("t");
                                ?>
                                <span>Data Fim</span>
                            </div>
                            <div class="dado data-dado">
                                <span><?= "$ultimoDia/$mes/$ano" ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Dados Usuario-->
            <div class="info">
                <div class="row-info">
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>ID</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $dados_user['id']?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>Nome</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $dados_user['nome']?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>Setor</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $dados_user['setor']?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>C. da folha de Ponto</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $data_atual?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </section>

    <script></script>
</body>

</html>