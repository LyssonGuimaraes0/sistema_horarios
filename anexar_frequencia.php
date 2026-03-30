<!DOCTYPE html>
<html lang="pt-BR">
<!-- Cabeçalho comum incluído -->
<?php

session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();

//Remove erros de Warning
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);

unset($_SESSION['formulario_exibido']);

// Verifica se já exibiu o formulário antes
$exibir_formulario_anexo = $_SESSION['exibir_formulario_anexo'] ?? false;

// Se for a primeira execução, não exibe
if (!$exibir_formulario_anexo) {
    $_SESSION['exibir_formulario_anexo'] = true; // marca que já exibiu
}

//Coleta dados do usuario
$dados_user = dados_user();
$registros_folha = folha_ponto_registro($dado_user['id'], $formata_mes, $ano_selecionado);

//Configuração para Dropdown inicia com valor selecionado pelo usuario
$mes_selecionado = $_POST['mes']
    ?? $_SESSION['mes_selecionado']
    ?? '';

$ano_selecionado = $_POST['ano']
    ?? $_SESSION['ano_selecionado']
    ?? '';

// salva POST na sessão
if (isset($_POST['mes']) && isset($_POST['ano'])) {
    $_SESSION['mes_selecionado'] = $_POST['mes'];
    $_SESSION['ano_selecionado'] = $_POST['ano'];
}



// pega mês/ano
$mes = $_POST['mes'] ?? $_SESSION['mes_selecionado'] ?? null;
$ano = $_POST['ano'] ?? $_SESSION['ano_selecionado'] ?? null;

if ($mes && $ano) {
    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
}

if (!empty($mes_selecionado) && !empty($ano_selecionado)) {
    $mes = $mes_selecionado;
    $ano = $ano_selecionado;

    $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
}



//coleta horarios registrados
$datas_registradas = horas_registradas($dados_user['id']);

//Coleta data atual e informações de mes e ano

$data = mese_atual();
$mes_atual = $data['mes'];
$meses = $data['meses'];
$ano_atual = $data['ano'];
$data_completa = $data['data_completa'];
$anolimite = $data['ano_limite'];

$mes_nome = $meses[$mes] ?? '';

$dias = null;


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
    <!-- Estrutura da Página -->
    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-primary">
                        <h1 class="title-container">Anexar Frequencia </h1>

                    </div>
                </div>

                <div class="container-home container-down">
                    <div class="container-dropdown">
                        <div class="row-dropdown">
                            <span>Selecione um periodo:</span>
                            <form method="post">
                                <span> Mês:</span>
                                <select class="dropdown" name="mes" id="selectMes">
                                    <?php
                                    foreach ($meses as $numero => $nome_mes):

                                        if ($mes_selecionado == "") {
                                            $selected = ((int) $numero === (int) $mes_atual) ? 'selected' : '';
                                        } else {
                                            $selected = ((int) $numero === (int) $mes_selecionado) ? 'selected' : '';
                                        }

                                        echo "<option data-mes='$numero' value='$numero' $selected> $nome_mes</option>";
                                    endforeach;
                                    ?>
                                </select>
                                <span> Ano:</span>
                                <select class="dropdown" name="ano" id="selectAno">
                                    <?php

                                    for ($i = $ano_atual; $i >= $anolimite; $i--): ?>
                                        <option value="<?= $i ?>" <?= ($ano_selecionado == $i) ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <button class="btn-formulario btn-registrar" type="submit"
                                    id="abrir-calendario">Busca</button>
                            </form>
                        </div>

                    </div>
                </div>
                <form action="./settings/registrar_ponto_mensal.php" method="post" enctype="multipart/form-data">
                    <div class="container-home container-down"
                        style="display:<?= ($exibir_formulario_anexo == true) ? 'block' : 'none' ?>">
                        <div class="container-anexar-frequencia">
                            <div class="calendario-header">

                                <div class="calendario-titulo">
                                    <span>Adione o anexo correspondente:</span>
                                </div>
                            </div>
                            <div class="container-upload">
                                <div class="item-upload">
                                    <span><?= $meses[$mes_selecionado] . "/" . $ano_selecionado ?></span>
                                    <!--Coleta os o arquivo e o periodo selecionado pelo usuario-->
                                    <?php
                                    $registros_folha_ponto = folha_ponto_registro($dados_user['id'], $mes_selecionado, $ano_selecionado);
                                    if (!empty($registros_folha_ponto)) {
                                        $mes_formatado = (int)$mes_selecionado;
                                        echo "<span style='margin-bottom:10px;'>Frequencia ja adicionada</span>";
                                        echo "<a href='" . $registros_folha_ponto[$mes_formatado]['caminho_folha_de_ponto'] . "' target='_blank' ><button class='btn-padrao' type='button'>Ver Frequencia</button></a>";


                                    } else {
                                        echo "<smalL>Arquivo permitido: PDF</small>";
                                        echo "<input class='btn-upload' style='margin-bottom:10px;' type='file' name='anexo_mes' required>";
                                        echo "<input type='hidden' name='ano' value='$ano_selecionado'>";
                                        echo "<input type='hidden' name='mes' value='$mes_selecionado'>";
                                        echo "<input class='btn-padrao' type='submit' value='Enviar'>";
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