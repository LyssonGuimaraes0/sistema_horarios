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
        /* padding: 3px; */
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
                        <h1>Folha de Ponto <?= "$nameMonth/$year"; ?></h1>
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
                                    <span><?= $dataStart ?></span>
                                </div>
                            </div>

                            <div class="row-data">
                                <div class="dado">
                                    <span>Data Fim</span>
                                </div>
                                <div class="dado data-dado">
                                    <span><?= $dataEnd ?></span>
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
                <tr>
                    <td class="dado">Empregado: <?= $user['nome'] ?> </td>
                    <td class="dado">Cargo: <?= $user['cargo'] ?> </td>
                    <td class="dado">Setor: <?= $user['setor'] ?> </td>
                    <td class="dado">Data de Criação: <?= $dateCurrent ?> </td>
                </tr>
            </tbody>
        </table>
        <!--Tabela-->
        <table class="row-info-horario" border="2">
            <thead>
                <tr>
                    <!--Cabecalho-->

                    <?php


                    if ($user['cargo'] != "Servidor Publico") {
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
            //Define as cores dos td
            $class = "";

            //Criação da Tabela
            foreach ($arrayDate as $allDate) {
                $dia = $allDate[0];           // date, weekName, weekend
                $attendance = $allDate['attendance'];
                $certificate = $allDate['certificate'];
                $feriado = $allDate['feriado'];

                $class = "";

                $date = date('d/m/Y', strtotime($dia['date']));
                $weekName = $dia['weekName'];
                $weekend = $dia['weekend'];

                if ($weekend || $feriado) {
                    $class = " class='tabela_fs'";
                }

                echo "<tr>";

                if ($user['cargo'] != "Servidor Publico") {
                    echo "<td{$class}>$date</td>";
                    echo "<td{$class}>" . ($attendance['entrada'] ?? '') . "</td>";
                    echo "<td{$class}>" . ($attendance['saida'] ?? '') . "</td>";
                } else {
                    echo "<td{$class}>$date</td>";
                    echo "<td{$class}>" . ($attendance['entrada'] ?? '') . "</td>";
                    echo "<td{$class}>" . ($attendance['saida_almoco'] ?? '') . "</td>";
                    echo "<td{$class}>" . ($attendance['volta_almoco'] ?? '') . "</td>";
                    echo "<td{$class}>" . ($attendance['saida'] ?? '') . "</td>";
                }

                // Fim de semana, feriado ou atestado
                if ($weekend) {
                    echo "<td{$class}>" . "Final de Semana</td>";
                } elseif ($feriado) {
                    echo "<td{$class}>" . "$feriado</td>"; // ex: "Carnaval", "Quarta-feira de Cinzas"
                } elseif ($certificate) {
                    echo "<td{$class}>" . "Atestado</td>";
                } else {
                    echo "<td{$class}>" . "</td>";
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
                <span>Assinatura de Coodernador</span>
            </div>
        </div>
        </div>
    </section>

    <script>

    </script>

</body>

</html>