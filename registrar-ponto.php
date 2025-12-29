<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();

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

                                <?php

                                foreach ($meses as $numero => $nome_mes):
                                    if ($numero > $mes_atual) {
                                        break;
                                    } ?>
                                    <option value="<?= $numero ?>" <?= ($mes_selecionado == $numero) ? 'selected' : '' ?>>
                                        <?= $nome_mes ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="dropdown" name="ano" id="">
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
            </div>
        </section>

        <section class="home-section">
            <div class="section-container">
                <!--Calendario Fica Oculto ate o usuario escolher o Mes-->
                <div class="container-home" style="display:<?= ($mes_selecionado != '') ? 'block' : 'none' ?>">
                    <div class="container-calendario calendario-container">
                        <div class="calendario-header">
                            <span>Calendario</span>
                            <br>
                            <span><?= $meses[$mes_selecionado] . "/" . $ano_selecionado ?> </span>
                        </div>

                        <form action="./settings/conf_data.php" method="post">
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

                                for ($dia = 1; $dia <= $dias; $dia++) {
                                    // Formato para pegar o nome do dia da semana
                                    $data_str = sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
                                    $registroDia = $datas_registradas[$data_str] ?? null;

                                    $nome_dia_ingles = date('l', strtotime($data_str));
                                    $nome_dia = $dias_semana[$nome_dia_ingles];

                                    //Verificação de caso existe algum registro no banco das datas
                            



                                    if ($nome_dia == "Sábado" || $nome_dia == "Domingo") {
                                        echo "
                                    <div class='linha-dia' style='margin-bottom:10px; padding:5px; border-bottom:1px solid #ddd;'>
                                        <strong>$data_completa ($nome_dia)</strong><br>
                                        <div>
                                            <span>Final de Semana</span>
                                        </div>
                                    </div>
                                    ";
                                    } else {
                                        echo "
                                        <div class='linha-dia' style='margin-bottom:10px; padding:5px; border-bottom:1px solid #ddd;'>
                                                <strong>$data_completa - $nome_dia</strong><br>";
                                        //Campos de entrada de dados para dias, adiciona readonly caso ja exista registro e adiciona botão de edição
                                        echo "<div class='container-horarios'>";
                                        echo "<div class='items-horarios'>";
                                        echo "<input class='horario-input' type='text' name='entrada[$dia]' value='" . (!empty($registroDia['entrada']) ? substr($registroDia['entrada'], 0, 5) : '') . "'" . (!empty($registroDia['entrada']) ? 'readonly' : '') . ">";
                                        echo (!empty($registroDia['entrada']) ? '<i class="fa-solid fa-pen-to-square editar-botao"></i>' : '');
                                        echo "</div>";

                                        echo "<div class='items-horarios'>";
                                        echo "<input class='horario-input' type='text' name='saida_pf[$dia]' value='" . (!empty($registroDia['saida_almoco']) ? substr($registroDia['saida_almoco'], 0, 5) : '') . "'" . (!empty($registroDia['saida_almoco']) ? 'readonly' : '') . ">";
                                        echo (!empty($registroDia['saida_almoco']) ? '<i class="fa-solid fa-pen-to-square editar-botao"></i>' : '');
                                        echo "</div>";

                                        echo "<div class='items-horarios'>";
                                        echo "<input class='horario-input' type='text' name='entrada_pf[$dia]'value='" . (!empty($registroDia['volta_almoco']) ? substr($registroDia['volta_almoco'], 0, 5) : '') . "'" . (!empty($registroDia['volta_almoco']) ? 'readonly' : '') . ">";
                                        echo (!empty($registroDia['volta_almoco']) ? '<i class="fa-solid fa-pen-to-square editar-botao"></i>' : '');
                                        echo "</div>";

                                        echo "<div class='items-horarios'>";
                                        echo "<input class='horario-input' type='text' name='saida[$dia]'value='" . (!empty($registroDia['saida']) ? substr($registroDia['saida'], 0, 5) : '') . "'" . (!empty($registroDia['saida']) ? 'readonly' : '') . " >";
                                        echo (!empty($registroDia['saida']) ? '<i class="fa-solid fa-pen-to-square editar-botao"></i>' : '');
                                        echo "</div>";

                                        if (!empty($registroDia['entrada']) || !empty($registroDia['saida_almoco']) || !empty($registroDia['volta_almoco']) || !empty($registroDia['saida'])) {
                                            echo "<i class='fa-solid fa-trash-can'></i>";
                                        }
                                        echo "</div>";
                                        echo "</div>";
                                    }
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

        console.log(codicao);
        console.log(mensagem);

        apresenta_modal(codicao, mensagem);
    </script>

</body>

</html>