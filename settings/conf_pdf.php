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
    <title>PDF</title>
</head>

<style>
    @page {
  size: A4 portrait;
  margin: 20mm;
}

.pagina-pdf {
  padding: 20px;
  width: 100%;
}

.container-pdf {
  width: 100%;
}

/*Cabeçalho*/

.cabecalho {
  display: grid;
  width: 100%;
  height: 80px;
  margin: 10px auto;
  margin-bottom: 25px;
  grid-template-columns: 1fr 0.3fr;
}

.cabecalho-titulo {
  border: 4px solid var(--primary-color);
  border-right: 0px;
  text-align: center;
}

.cabecalho-titulo h1 {
  padding: 15px;
}

.periodo {
  background-color: var(--primary-color);
  text-align: center;
  height: 30px;
  padding: 5px;
}

.periodo span {
  color: var(--white-text);
}

.row-data {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
}

.row-info {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  align-items: center;
  gap: 12px;
}

.row-info-horario{
  width: 100%;
}

.dado.texto.tabela{
  height: 55px;
}

.info{
  margin-bottom: 15px;
}

.item-info {
  display: grid;
  grid-template-columns: 1fr;
}

.item-dado {
  display: grid;
  grid-template-columns: 0.6fr 1fr;
}


.dado {
  height: 30px;
  text-align: center;
  padding: 2px;
  border: 1px solid rgb(41, 40, 40);
}

.dado.texto {
  background-color: var(--primary-color-hover);
  color: var(--white-text);
}

.dado.texto span {
  color: var(--white-text);
}

/*Tabela do PDF*/

.tabela_fs{
  background-color: rgba(128, 128, 128, 0.205);
}

.assinatura-row{
  margin: 140px auto 0 auto;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 200px;
  max-width: 800px;
}

.linha-assinatura{
  width: 300px;
  height: 1px;
  background: grey;
}

.assinatura-item{
  text-align: center;
}
</style>

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
                            <span><?= $dados_user['id'] ?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>Nome</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $dados_user['nome'] ?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>Setor</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $dados_user['setor'] ?></span>
                        </div>
                    </div>
                    <div class="item-dado">
                        <div class="dado texto">
                            <span>C. da folha de Ponto</span>
                        </div>
                        <div class="dado data-dado">
                            <span><?= $data_atual ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!--Tabela-->
            <table class="row-info-horario" border="2">
                <thead>
                    <!--Cabecalho-->
                    <th class="dado texto tabela">Data</th>
                    <th class="dado texto tabela">Entrada</th>
                    <th class="dado texto tabela">Saida Almoço</th>
                    <th class="dado texto tabela">Volta Almoço</th>
                    <th class="dado texto tabela">Saida</th>
                    <th class="dado texto tabela">Observação</th>
                    <th class="dado texto tabela">Data</th>
                    <th class="dado texto tabela">Entrada</th>
                    <th class="dado texto tabela">Saida Almoço</th>
                    <th class="dado texto tabela">Volta Almoço</th>
                    <th class="dado texto tabela">Saida</th>
                    <th class="dado texto tabela">Observação</th>
                </thead>
                <?php
                $datas = [];
                //Utiliza Varial $ultimoDia criado anteriomente
                for ($dia = 1; $dia <= $ultimoDia; $dia++) {
                    $datas[] = date("d/m/Y", strtotime("$ano-$mes-$dia"));
                    $limitecoluna1 = ceil(count($datas) / 2);

                    $totalLinhas = $limitecoluna1;
                }


                //Criação da Tabela

                for ($i = 0; $i < $totalLinhas; $i++) {
                    //Definindo datas para serem apresentadas
                    $data1 = $datas[$i];
                    $data2 = $datas[$i + $limitecoluna1] ? $datas[$i + $limitecoluna1] : "";

                    //Coleta registros do banco
                    $datas_registradas = horas_registradas($dados_user['id']);

                    $diaColuna1 = $i + 1;
                    $diaColuna2 = $diaColuna1 + $limitecoluna1;

                    $dataColuna1 = sprintf('%04d-%02d-%02d', $ano, $mes, $diaColuna1);
                    $dataColuna2 = sprintf('%04d-%02d-%02d', $ano, $mes, $diaColuna2);

                    //Nome do Dia
                    $nomediaColuna1 = date('l', strtotime($dataColuna1));
                    $nomediaColuna2 = date('l', strtotime($dataColuna2));

                    $registroColuna1 = $datas_registradas[$dataColuna1] ?? null;
                    $registroColuna2 = $datas_registradas[$dataColuna2] ?? null;
                    //Coleta de valores da data relacionada
                    $entradaColuna1 = $registroColuna1['entrada'] ?? null;
                    $entradaColuna2 = $registroColuna2['entrada'] ?? null;
                    $saidaAlmocoColuna1 = $registroColuna1['saida_almoco'] ?? null;
                    $saidaAlmocoColuna2 = $registroColuna2['saida_almoco'] ?? null;
                    $voltaAlmocoColuna1 = $registroColuna1['volta_almoco'] ?? null;
                    $voltaAlmocoColuna2 = $registroColuna2['volta_almoco'] ?? null;
                    $saidaColuna1 = $registroColuna1['saida'] ?? null;
                    $saidaColuna2 = $registroColuna2['saida'] ?? null;
                    $statusColuna1 = $registroColuna1['status_dia'] ?? null;
                    $statusColuna2 = $registroColuna2['status_dia'] ?? null;


                    echo "<tr>";
                    if ($nomediaColuna1 === "Saturday" || $nomediaColuna1 === "Sunday") {

                        echo "<td class='tabela_fs'>$data1</td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'>Final de Semana</td>";
                    } elseif ($statusColuna1 === "Atestado") {

                        echo "<td>$data1</td>";
                        echo "<td>$entradaColuna1</td>";
                        echo "<td>$saidaAlmocoColuna1</td>";
                        echo "<td>$voltaAlmocoColuna1</td>";
                        echo "<td>$saidaColuna1</td>";
                        echo "<td class=''>Atestado</td>";
                    } else {

                        echo "<td>$data1</td>";
                        echo "<td>$entradaColuna1</td>";
                        echo "<td>$saidaAlmocoColuna1</td>";
                        echo "<td>$voltaAlmocoColuna1</td>";
                        echo "<td>$saidaColuna1</td>";
                        echo "<td></td>";
                    }
                    if ($nomediaColuna2 === "Saturday" || $nomediaColuna2 === "Sunday") {

                        echo "<td class='tabela_fs'>$data2</td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'></td>";
                        echo "<td class='tabela_fs'>Final de Semana</td>";
                    } elseif ($statusColuna2 === "Atestado") {

                        echo "<td class='tabela_at'>$data2</td>";
                        echo "<td>$data2</td>";
                        echo "<td>$entradaColuna2</td>";
                        echo "<td>$saidaAlmocoColuna2</td>";
                        echo "<td>$voltaAlmocoColuna2</td>";
                        echo "<td>$saidaColuna2</td>";
                        echo "<td class='tabela_at'>Atestado</td>";
                    } else {

                        echo "<td>$data2</td>";
                        echo "<td>$entradaColuna2</td>";
                        echo "<td>$saidaAlmocoColuna2</td>";
                        echo "<td>$voltaAlmocoColuna2</td>";
                        echo "<td>$saidaColuna2</td>";
                        echo "<td></td>";
                    }
                    echo "</tr>";
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
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render();

// Mostra o PDF no navegador
$dompdf->stream("Folha_Ponto_<?= $nome_mes ?>_<?= $ano ?>.pdf", [
    "Attachment" => false // false = abre no navegador | true = baixa
]);
exit;


?>