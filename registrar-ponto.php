<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();
limparFiltros();


$dados_user = dados_user();

//Configuração para Dropdown inicia com valor selecionado pelo usuario
$mes_selecionado = $_POST['mes'] ?? '';
$ano_selecionado = $_POST['ano'] ?? '';

//Coleta data atual e informações de mes e ano

$data = mese_atual();
$mes_atual = $data['mes'];
$mes_nome = $data['mes_nome'];
$meses = $data['meses'];
$ano_atual = $data['ano'];
$data_completa = $data['data_completa'];
$anolimite = $data['ano_limite'];




//Configurações de Mes é Ano
$mes = $_POST['mes'] ?? null;
$ano = $_POST['ano'] ?? null;
$dias = null;

if ($mes && $ano) {
    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
}
?>

<?php include('./snippets/head.html'); ?>

<body>

    <!-- Estrutura Modal-->
    <?php include('./snippets/modal.html'); ?>

    <!-- Navbar -->

    <?php if (verificar_permissoes($dados_user) == true) {
        include('./snippets/navbar-admin.html');
    } else {
        include('./snippets/navbar.html');
    }
    ?>
    <!-- Estrutura da Home -->
    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-white">
                        <h1 class="title-container">Registro de Horario </h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="home-section home-down">
            <div class="section-container">
                <div class="container-home container-down">
                    <div class="container-dropdown">
                        <div class="row-dropdown">
                            <span>Selecione um periodo:</span>
                            <form method="post">
                                <span> Mês:</span>
                                <select class="dropdown" name="mes" id="selectMes">
                                    <?php

                                    foreach ($meses as $numero => $nome_mes): ?>
                                        <option data-mes="<?= $numero ?>" value="<?= $numero ?>" <?= ($mes_selecionado == $numero) ? 'selected' : '' ?>>
                                            <?= $nome_mes ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <span> Ano:</span>
                                <select class="dropdown" name="ano" id="selectAno">
                                    <?php

                                    for ($i = $ano_atual; $i >= $anolimite; $i--): ?>
                                        <option value="<?= $i ?>" <?= ($ano_selecionado == $i) ? 'selected' : '' ?>><?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <button class="btn-formulario btn-registrar" type="submit" id="abrir-calendario">Carregar</button>

                            </form>
                            <!--Botão de enviar PDF-->
                            <div class="contaienr-pdf">
                                <form action="./settings/conf_pdf.php" method="post" target="_blank">
                                    <input type="hidden" name="mes_pdf" value="<?= $mes ?>">
                                    <input type="hidden" name="ano_pdf" value="<?= $ano ?>">
                                    <input class="btn-formulario" type="submit" value="Imprimir">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form action="./settings/conf_data.php" method="post">
            <section class="home-section home-up">
                <div class="section-container">
                    <!--Calendario Fica Oculto ate o usuario escolher o Mes-->
                    <div style="display:<?= ($mes_selecionado != '') ? 'block' : 'none' ?>">
                        <div class="container-calendario calendario-container">
                            <div class="calendario-header">
                                <div class="calendario-titulo">
                                    <span>Folha de Ponto</span>
                                </div>
                                <button type='submit' class='btn-calendario'>Salvar alterações</button>
                            </div>
                            <div class="calendario-body">
                                <!--Leva as variaveis para o proximo formulario-->
                                <input type="hidden" name="mes" value="<?= $mes ?>">
                                <input type="hidden" name="ano" value="<?= $ano ?>">
                                <input type="hidden" name="dias" value="<?= $dias ?? '' ?>">
                                <?php
                                if ($mes && $ano) {

                                    $dias_semana = [
                                        'Sunday' => 'Domingo',
                                        'Monday' => 'Segunda-feira',
                                        'Tuesday' => 'Terça-feira',
                                        'Wednesday' => 'Quarta-feira',
                                        'Thursday' => 'Quinta-feira',
                                        'Friday' => 'Sexta-feira',
                                        'Saturday' => 'Sábado'
                                    ];

                                    //Coleta os dados ja registrados anteriomente
                                    $datas_registradas = horas_registradas($_SESSION['user_id']);

                                    $dia_proximo = "";
                                    for ($dia = 1; $dia <= $dias; $dia++) {
                                        // Formato para pegar o nome do dia da semana
                                        $data_str = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);

                                        //Data formatada para formato brasileiro
                                        $data_brasil = sprintf('%02d/%02d/%04d', $dia, $mes, $ano);
                                        $data_dia = sprintf('%02d', $dia);
                                        $registroDia = $datas_registradas[$data_str] ?? null;

                                        $nome_dia_ingles = date('l', strtotime($data_str));
                                        $nome_dia = $dias_semana[$nome_dia_ingles];
                                        $status_dia = $registroDia['status_dia'] ?? null;

                                        //Verificação de caso existe algum registro no banco das datas
                                        if ($data_completa === $data_brasil) {
                                            $dia_proximo = "dia atual";
                                        } else if ($data_completa < $data_brasil) {
                                            $dia_proximo = "proximo dia";
                                        }

                                        echo "<div class='linha-dia" . (($dia_proximo === "proximo dia") ? " proximo-dia" : "") . " '>";
                                        if ($nome_dia == "Sábado" || $nome_dia == "Domingo") {
                                            echo "<div class='container-horarios'>";
                                            echo "<div class='circule-data" . (($dia_proximo === "dia atual") ? " circule-dia" : "") . "'><span>$data_dia</span></div>";
                                            echo "<div class='linha-vertical'></div>";
                                            echo "<div class='items-horarios'>
                                                    <strong>$nome_dia</strong><br>
                                                    <span>$mes_nome de $ano_selecionado</span>
                                                  </div>
                                            ";
                                            echo "<div class='items-horarios input-colunm'>";
                                            echo "<span>Final de Semana</span>";
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        } else {


                                            //Campos de entrada de dados para dias, adiciona readonly caso ja exista registro e adiciona botão de edição

                                            echo ($status_dia === "Atestado") ? "<span>Atestado</span>" : "";
                                            echo "<div class='container-horarios'>";
                                            echo "<div class='circule-data" . (($dia_proximo === "dia atual") ? " circule-dia" : "") . "'><span>$data_dia</span></div>";
                                            echo "<div class='linha-vertical'></div>";
                                            echo "<div class='items-horarios'>
                                                    <strong>$nome_dia</strong><br>
                                                    <span>$mes_nome de $ano_selecionado</span>
                                                  </div>
                                            ";
                                            echo "<div class='items-horarios input-colunm'>";
                                            echo "<span>Entrada</span>";
                                            echo "<input class='horario-input' maxlength='5' type='time' name='entrada[$dia]' value='" . (!empty($registroDia['entrada']) ? substr($registroDia['entrada'], 0, 5) : '') . "'" . (!empty($registroDia['entrada']) ? 'readonly' : '') . ">";
                                            echo "</div>";

                                            echo "<div class='items-horarios input-colunm'>";
                                            echo "<span>Intervalo inicio</span>";
                                            echo "<input class='horario-input' maxlength='5' type='time' name='saida_pf[$dia]' value='" . (!empty($registroDia['saida_almoco']) ? substr($registroDia['saida_almoco'], 0, 5) : '') . "'" . (!empty($registroDia['saida_almoco']) ? 'readonly' : '') . ">";
                                            echo "</div>";

                                            echo "<div class='items-horarios input-colunm'>";
                                            echo "<span>Intervalo volta</span>";
                                            echo "<input class='horario-input' maxlength='5' type='time' name='entrada_pf[$dia]'value='" . (!empty($registroDia['volta_almoco']) ? substr($registroDia['volta_almoco'], 0, 5) : '') . "'" . (!empty($registroDia['volta_almoco']) ? 'readonly' : '') . ">";
                                            echo "</div>";

                                            echo "<div class='items-horarios input-colunm'>";
                                            echo "<span>Saida</span>";
                                            echo "<input class='horario-input' maxlength='5' type='time' name='saida[$dia]'value='" . (!empty($registroDia['saida']) ? substr($registroDia['saida'], 0, 5) : '') . "'" . (!empty($registroDia['saida']) ? 'readonly' : '') . " >";
                                            echo "</div>";

                                            echo "<div class='items-botoes'>";


                                            if (!empty($registroDia['entrada']) || !empty($registroDia['saida_almoco']) || !empty($registroDia['volta_almoco']) || !empty($registroDia['saida'])) {
                                                echo "<i class='fa-solid fa-pen-to-square botao-calendario' onclick=\"editar_horario(this)\"></i>";
                                                echo "<i class='fa-solid fa-trash-can botao-calendario' onclick=\"remover_horario('$data_str','$data_brasil')\"></i>";
                                            } else {
                                                echo "<i class='fa-solid fa-file-alt botao-calendario' onclick=\"adicionar_justificativa('$data_str')\"></i>";
                                            }
                                            echo "</div>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>

        </form>



        </section>
    </div>
    <!-- Estrutura da script -->

    <?php include('./snippets/script.html') ?>
    <script>
        //Apresenta Mes decorrente do Ano atual
        document.addEventListener('DOMContentLoaded', () => {
            const selectAno = document.getElementById('selectAno');
            const selectMes = document.getElementById('selectMes');

            const anoAtual = <?= $ano_atual ?>;
            const mesAtual = <?= $mes_atual ?>;


            // 🔹 estado que se atualiza automaticamente
            const estado = {
                ano: parseInt(selectAno.value),
                mes: parseInt(selectMes.value) || null
            };


            function atualizarEstado() {
                estado.ano = parseInt(selectAno.value);
                estado.mes = selectMes.value ? parseInt(selectMes.value) : null;

            }

            function atualizarMeses() {
                const anoSelecionado = parseInt(selectAno.value);

                [...selectMes.options].forEach(option => {
                    const mes = parseInt(option.dataset.mes);

                    if (anoSelecionado === anoAtual && mes > mesAtual) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                });

                // Se o mês selecionado ficar inválido, limpa
                if (selectMes.selectedOptions.length) {
                    const mesSelecionado = parseInt(selectMes.value);
                    if (anoSelecionado === anoAtual && mesSelecionado > mesAtual) {
                        selectMes.value = mesAtual;
                    }
                }

                // 🔹 sempre sincroniza depois das regras
                atualizarEstado();
            }

            selectAno.addEventListener('change', atualizarMeses);
            selectMes.addEventListener('change', atualizarEstado);

            // 🚀 inicialização
            atualizarMeses();
        });



        //Verifica caso o botão de lixeira foi precionado
        function remover_horario(data, data_visualizacao) {
            document.getElementById('data_delete').value = data;
            document.getElementById('data-remocao').innerText = data_visualizacao;
            document.getElementById('modal-delete').style.display = 'flex';
        }



        //Coleta valor recebido em conf_data.php é armazena
        <?php $cadastro = $_SESSION['cadastro'] ?? null;
        $mensagem = $_SESSION['mensagem'] ?? null;
        //Limpa valor anterior para novos cadastros!
        unset($_SESSION['cadastro']);
        unset($_SESSION['mensagem']);
        ?>

        //Apresenta modal caso cadastro tenha falhado ou realizado com sucesso
        var codicao = <?php echo json_encode($cadastro); ?>;
        var mensagem = <?php echo json_encode($mensagem); ?>;

        apresenta_modal(codicao, mensagem);
    </script>

</body>

</html>