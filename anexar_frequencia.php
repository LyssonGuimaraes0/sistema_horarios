<!DOCTYPE html>
<html lang="pt-BR">
<!-- Cabeçalho comum incluído -->
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();

$dados_user = dados_user();

//coleta horarios registrados
$datas_registradas = horas_registradas($dados_user['id']);

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
    <!-- Estrutura da Página -->
    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container">Anexar Frequencia </h2>
                    </div>
                </div>
                <div class="container-home">
                    <div class="container-dropdown">
                        <form method="post">
                            <select class="dropdown" name="mes" id="selectMes">
                                <?php
                                foreach ($meses as $numero => $nome_mes): ?>
                                    <option data-mes="<?= $numero ?>" value="<?= $numero ?>" <?= ($mes_selecionado == $numero) ? 'selected' : '' ?>>
                                        <?= $nome_mes ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select class="dropdown" name="ano" id="selectAno">
                                <?php

                                $anolimite = "2024";

                                for ($i = $ano_atual; $i >= $anolimite; $i--): ?>
                                    <option value="<?= $i ?>" <?= ($ano_selecionado == $i) ? 'selected' : '' ?>><?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" id="abrir-calendario">Busca</button>

                        </form>
                    </div>
                </div>

                <form action="./settings/registrar_ponto_mensal.php" method="post" enctype="multipart/form-data">
                    <div class="container-home" style="display:<?= ($mes_selecionado != '') ? 'block' : 'none' ?>">
                        <div class="container-calendario calendario-container">
                            <div class="calendario-header">
                                <div class="calendario-titulo">
                                    <span>Adione o anexo correspondente:</span>
                                    <br>
                                    <span><?= $meses[$mes_selecionado] . "/" . $ano_selecionado ?></span>
                                </div>
                            </div>
                            <div>
                                <!--Coleta os o arquivo e o periodo selecionado pelo usuario-->
                                <?php

                                if ($registros_folha_ponto = folha_ponto_registro($dados_user['id'], $mes_selecionado, $ano_selecionado)) {
                                    echo "<span>Vc ja possui registro para esse periodo</span>";
                                } else {
                                    echo "<input type='file' name='anexo_mes'>";
                                    echo "<input type='hidden' name='ano' value='$ano_selecionado'>";
                                    echo "<input type='hidden' name='mes' value='$mes_selecionado'>";
                                    echo "<input type='submit' value='Enviar'>";
                                }

                                ?>

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