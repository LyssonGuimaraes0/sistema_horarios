<?php
require __DIR__ . '/../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

ob_start();

error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);
include("conf_server.php");
include("conf_bd.php");
$conn = conexao_banco();
session_start();
verificar_sessao();

//Configuração de dados do usuario
$dados_user = dados_user();
$cargo = horario_cargo();
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
    "01" => 'Janeiro',
    "02" => 'Fevereiro',
    "03" => 'Março',
    "04" => 'Abril',
    "05" => 'Maio',
    "06" => 'Junho',
    "07" => 'Julho',
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
    <title>PDF</title>
</head>

<style>
    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9px;
    }

    .pagina-pdf {
        width: 100%;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* CRÍTICO */
    }

    th,
    td {
        border: 1px solid #000;
        padding: 3px;
        text-align: center;
        vertical-align: middle;
        word-wrap: break-word;
    }



    .container-meses {
        width: 100%;
    }

    .periodo-span {
        font-size: 12px;
    }

    .cabecalho-mes {
        display: inline-block;
        vertical-align: middle;
    }

    .row-data {
        display: inline-block;
        width: 120px;
        margin: 5px 0;
        text-align: center;
    }

    /* Cabeçalho */
    h1 {
        font-size: 14px;
        margin: 5px 0;
    }


    .dado {
        font-size: 12px;
    }

    /* Ajuste das colunas */
    .col-data {
        width: 12%;
    }

    .col-hora {
        width: 10%;
    }

    .col-obs {
        width: 28%;
    }

    /* Final de semana */
    .tabela_fs {
        background-color: #f0f0f0;
        font-style: italic;
    }

    /* Assinaturas */
    .assinatura-row {
        margin-top: 20px;
        display: table;
        width: 100%;
        margin-top: 50px;
    }

    .assinatura-item {
        display: table-cell;
        text-align: center;
    }

    .linha-assinatura {
        border-bottom: 1px solid #000;
        width: 80%;
        margin: 0 auto 5px auto;
    }
</style>

<body>
    <section class="pagina-pdf">
        <table class="row-info-horario">
            <thead>
                <!--Cabecalho-->
                <th class="tabela">
                    <div class="cabecalho-titulo">
                        <h1>Folha de Ponto <?= "$nome_mes/$ano" ?></h1>
                    </div>
                </th>
                <th class="dado texto tabela" style="vertical-align: middle; text-align: center;">
                    <div class="cabecalho-mes">
                        <div class="periodo">
                            <span class="periodo-span">Período</span>
                        </div>
                        <div class="container-meses">
                            <div class="row-data">
                                <div class="dado">
                                    <span>Data Inicio</span>
                                </div>
                                <div class="dado data-dado">
                                    <span><?= "01/$mes/$ano" ?></span>
                                </div>
                            </div>

                            <div class="row-data">
                                <div class="dado">
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
                </th>
            </thead>
        </table>

        <!--Dados Usuario-->
        <table class="row-info-horario" class="row-info-horario" border="1">
            <tbody>
                <td class="dado">Empregado: <?= $dados_user['nome'] ?> </td>
                <td class="dado">Cargo: <?= $cargo['cargo'] ?> </td>
                <td class="dado">Setor: <?= $dados_user['setor'] ?> </td>
                <td class="dado">C. da folha de Ponto: <?= $data_atual ?> </td>
            </tbody>
        </table>
        <!--Tabela-->
        <table class="row-info-horario" border="2">
            <thead>
                <tr>
                    <!--Cabecalho-->
                    <?php

                    if ($cargo['cargo'] == "Estágiario-Manha" || $cargo['cargo'] == "Estágiario-Tarde") {
                        echo "<th class='dado texto tabela'>Data</th>";
                        echo "<th class='dado texto tabela'>Entrada</th>";
                        echo "<th class='dado texto tabela'>Saida</th>";
                        echo "<th class='dado texto tabela'>Observação</th>";
                    } else {
                        echo "<th class='dado texto tabela'>Data</th>";
                        echo "<th class='dado texto tabela'>Entrada</th>";
                        echo "<th class='dado texto tabela'>Saida Almoço</th>";
                        echo "<th class='dado texto tabela'>Volta Almoço</th>";
                        echo "<th class='dado texto tabela'>Saida</th>";
                        echo "<th class='dado texto tabela'>Observação</th>";
                    }
                    ?>



                </tr>

            </thead>
            <?php
            $datas = [];
            //Utiliza Varial $ultimoDia criado anteriomente
            for ($dia = 1; $dia <= $ultimoDia; $dia++) {
                $datas[] = date("d/m/Y", strtotime("$ano-$mes-$dia"));
                $totalLinhas = ceil(count($datas));
            }


            //Criação da Tabela
            
            for ($i = 0; $i < $totalLinhas; $i++) {
                //Definindo datas para serem apresentadas
                $data1 = $datas[$i];

                //Coleta registros do banco
                $datas_registradas = horas_registradas($dados_user['id']);

                $diaColuna1 = $i + 1;

                $dataColuna1 = sprintf('%04d-%02d-%02d', $ano, $mes, $diaColuna1);

                //Busca feriados
                $feriados = feriados($ano);

                //Nome do Dia
                $nomediaColuna1 = date('l', strtotime($dataColuna1));

                $registroColuna1 = $datas_registradas[$dataColuna1] ?? null;

                //Coleta de valores da data relacionada
            
                //Coleta dados do dia caso seja atestado ou Feriado
                $statusColuna1 = $registroColuna1['status_dia'] ?? null;


                if ($cargo['cargo'] == "Estágiario-Manha" || $cargo['cargo'] == "Estágiario-Tarde") {
                    echo "<tr>";
                    //Coleta de valores da data relacionada
                    $entradaColuna1 = $registroColuna1['entrada'] ?? null;
                    $saidaColuna1 = $registroColuna1['saida'] ?? null;
                    $statusColuna1 = $registroColuna1['status_dia'] ?? null;

                    if ($nomediaColuna1 === "Saturday" || $nomediaColuna1 === "Sunday" || $feriados[$data1] != null) {

                        echo "<td class='tabela_fs'>$data1</td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        //Verifica se oq esta presente em $statusColuna1 é atestado ou se é o feriado
                        echo "<td class='tabela_fs'>" . ($feriados[$data1] != null ? $feriados[$data1] : "Fim de Semana") . "</td>";

                    } elseif ($statusColuna1 == "Atestado") {

                        echo "<td>$data1</td>";
                        echo "<td>$entradaColuna1</td>";
                        echo "<td>$saidaColuna1</td>";
                        echo "<td class=''>Atestado</td>";

                    } else {

                        echo "<td>$data1</td>";
                        echo "<td>$entradaColuna1</td>";
                        echo "<td>$saidaColuna1</td>";
                        echo "<td></td>";
                    }
                    echo "</tr>";


                } else {

                    $entradaColuna1 = $registroColuna1['entrada'] ?? null;
                    $saidaAlmocoColuna1 = $registroColuna1['saida_almoco'] ?? null;
                    $voltaAlmocoColuna1 = $registroColuna1['volta_almoco'] ?? null;
                    $saidaColuna1 = $registroColuna1['saida'] ?? null;

                    echo "<tr>";

                    if ($nomediaColuna1 == "Saturday" || $nomediaColuna1 == "Sunday" || $feriados[$data1] != null || $statusColuna1 == "Atestado") {

                        echo "<td class='tabela_fs'>$data1</td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        if ($statusColuna1 == "Atestado") {
                            echo "<td class='tabela_fs'>Atestado</td>";
                        } else {
                            echo "<td class='tabela_fs'>" . ($feriados[$data1] != null ? $feriados[$data1] : "Fim de Semana") . "</td>";
                        }

                    } else {

                        echo "<td>$data1</td>";
                        echo "<td>$entradaColuna1</td>";
                        echo "<td>$saidaAlmocoColuna1</td>";
                        echo "<td>$voltaAlmocoColuna1</td>";
                        echo "<td>$saidaColuna1</td>";
                        echo "<td></td>";
                    }
                    echo "</tr>";

                }


            }

            ?>
        </table>

        <div class="assinatura-row">
            <div class="assinatura-item">
                <div class="linha-assinatura"></div>
                <span>Assinatura de Empregado</span>
            </div>

            <div class="assinatura-item">
                <div class="linha-assinatura"></div>
                <span>Assinatura de RH</span>
            </div>
        </div>
        </div>


    </section>

</body>

</html>

<?php

$html = ob_get_clean(); // captura todo HTML

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Mostra o PDF no navegador
$nomePDF = "Folha_Ponto";
$dompdf->stream("Folha_Ponto_$nome_mes" . "_" . " $ano.pdf", [
    "Attachment" => false // false = abre no navegador | true = baixa
]);
exit;


?>