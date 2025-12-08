<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
session_start();
//Configura um tempo de inatividade para desconectar!
time_out();
$dados_user = dados_user();

//Configura 

?>


<?php include('./snippets/head.html'); ?>

<body>
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
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereio</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8">Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                            <select class="dropdown" name="ano" id="">
                                <?php
                                $anoatual = date('Y');
                                $anolimite = "2024";
                                ?>
                                <?php for ($ano = $anoatual; $ano >= $anolimite; $ano--): ?>
                                    <option value="<?= $ano ?>"><?= $ano ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="button" id="abrir-calendario" >Busca</button>

                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-calendario calendario-container">
                        <div class="calendario-header">
                            <span>Calendario</span>
                        </div>

                        <form action="./settings/conf_data.php" method="post">
                            <!--Leva as variaveis para o proximo formulario-->
                            <input type="hidden" name="mes" value="<?= $mes ?>">
                            <input type="hidden" name="ano" value="<?= $ano ?>">
                            <?php
                            if (isset($_POST['mes']) && isset($_POST['ano'])) {
                                //Coleta dados
                                $mes = $_POST['mes'];
                                $ano = $_POST['ano'];

                                // Quantos dias tem o mês
                                $dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                                // Array dos dias da semana em português
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
                                        <input type='text' name='entrada[$dia]' placeholder='Entrada (Ex: 08:00)'>
                                        <input type='text' name='saida_pf[$dia]' placeholder='Almoço (Ex: 08:00)'>
                                        <input type='text' name='entrada_pf[$dia]' placeholder='Retorno Almoço (Ex: 08:00)'>
                                        <input type='text' name='saida[$dia]' placeholder='Saída (Ex: 17:00)'>
                                    </div>
                                    ";
                                }
                            }
                            ?>
                            <button type="submit" class="btn-login">Enviar datas</button>
                        </form>

                    </div>
                </div>
            </div>

        </section>
    </div>

    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>

</body>

</html>