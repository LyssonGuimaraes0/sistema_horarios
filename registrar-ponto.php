<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
include('./settings/conf_bd.php');
include('./settings/conf_server.php');

verificar_sessao();
$dados_user = dados_user();

//Configuração para Dropdown inicia com valor selecionado pelo usuario
$mes_selecionado = $_POST['mes'] ?? '';
$ano_selecionado = $_POST['ano'] ?? '';
$mes_atual = meses($mes_selecionado) ?? '';  

//Configurações de Mes é Ano
$mes  = $_POST['mes'] ?? null;
$ano  = $_POST['ano'] ?? null;
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
                    <div class="container-welcome">
                        <h2 class="title-container">Registro de Horario </h2>
                    </div>
                </div>
            </div>
        </section>
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-dropdown">
                        <form method="post">
                            <select class="dropdown" name="mes">
                                <option value="1" <?=  ($mes_selecionado == 1) ? 'selected' : '' ?>>Janeiro</option>
                                <option value="2" <?=  ($mes_selecionado == 2) ? 'selected' : '' ?>>Fevereio</option>
                                <option value="3" <?=  ($mes_selecionado == 3) ? 'selected' : '' ?>>Março</option>
                                <option value="4" <?=  ($mes_selecionado == 4) ? 'selected' : '' ?>>Abril</option>
                                <option value="5" <?=  ($mes_selecionado == 5) ? 'selected' : '' ?>>Maio</option>
                                <option value="6" <?=  ($mes_selecionado == 6) ? 'selected' : '' ?>>Junho</option>
                                <option value="7" <?=  ($mes_selecionado == 7) ? 'selected' : '' ?>>Julho</option>
                                <option value="8" <?=  ($mes_selecionado == 8) ? 'selected' : '' ?>>Agosto</option>
                                <option value="9" <?=  ($mes_selecionado == 9) ? 'selected' : '' ?>>Setembro</option>
                                <option value="10" <?=  ($mes_selecionado == 10) ? 'selected' : '' ?>>Outubro</option>
                                <option value="11" <?=  ($mes_selecionado == 11) ? 'selected' : '' ?>>Novembro</option>
                                <option value="12" <?=  ($mes_selecionado == 12) ? 'selected' : '' ?>>Dezembro</option>
                            </select>
                            <select class="dropdown" name="ano" id="">
                                <?php
                                $anoatual = date('Y');
                                $anolimite = "2024";

                                for ($a = $anoatual; $a >= $anolimite; $a--): ?>
                                    <option value="<?= $a ?>"<?=  ($ano_selecionado == $a) ? 'selected' : '' ?>><?= $a ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="submit" id="abrir-calendario">Busca</button>

                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="section-container">
                                <!--Calendario Fica Oculto ate o usuario escolher o Mes-->
                <div class="container-home" style="display:<?= ($mes_selecionado != '') ? 'block' : 'none' ?>">
                    <div class="container-calendario calendario-container">
                        <div class="calendario-header">
                            <span>Calendario</span>
                            <span><?= $mes_atual . "/" . $ano_selecionado?>  </span>
                        </div>

                        <form action="./settings/conf_data.php" method="post">
                            <!--Leva as variaveis para o proximo formulario-->
                            <input type="hidden" name="mes" value="<?= $mes ?>">
                            <input type="hidden" name="ano" value="<?= $ano ?>">
                            <input type="hidden" name="dias" value="<?= $dias ?? '' ?>">
                            <?php
                            if ($mes && $ano) {

                                $dias_semana = [
                                    'Sunday'    => 'Domingo',
                                    'Monday'    => 'Segunda-feira',
                                    'Tuesday'   => 'Terça-feira',
                                    'Wednesday' => 'Quarta-feira',
                                    'Thursday'  => 'Quinta-feira',
                                    'Friday'    => 'Sexta-feira',
                                    'Saturday'  => 'Sábado'
                                ];
                                for ($dia = 1; $dia <= $dias; $dia++) {
                                    // Formato para pegar o nome do dia da semana
                                    $data_str = "$ano-$mes-$dia";
                                    $nome_dia_ingles = date('l', strtotime($data_str));
                                    $nome_dia = $dias_semana[$nome_dia_ingles];
                                    echo "
                                    <div class='linha-dia' style='margin-bottom:10px; padding:5px; border-bottom:1px solid #ddd;'>
                                        <strong>$dia ($nome_dia)</strong><br>
                                        <div>
                                        <input type='text' name='entrada[$dia]' placeholder='Entrada'>
                                        <input type='text' name='saida_pf[$dia]' placeholder='Almoço'>
                                        <input type='text' name='entrada_pf[$dia]' placeholder='Retorno Almoço'>
                                        <input type='text' name='saida[$dia]' placeholder='Saída'>
                                        </div>
                                    </div>
                                    ";
                                }
                                echo "<button type='submit' class='btn-login'>Enviar datas</button>";
                            }
                            ?>
                            
                        </form>

                    </div>
                </div>
            </div>

        </section>
    </div>
    <!-- Estrutura da script -->

    <?php include('./snippets/script.html') ?>
    <script>
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