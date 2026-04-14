<?php

require_once dirname(__DIR__, 2) . '/settings/config.php';

require_once HELPER_PATH . '/SessionHelper.php';
require_once HELPER_PATH . '/ModalHelper.php';
require_once HELPER_PATH . '/DatasHelper.php';
require_once HELPER_PATH . '/AuthHelper.php';
require_once CONTROLLER_PATH . '/DatasController.php';
require_once CONTROLLER_PATH . '/UsuarioControllers.php';


iniciar_sessao();

//Coleta respostas de outras Páginas
$resposta = VerificarRespostaModal();

//Coleta dados do usuario
$dados_user = CarregarDadosUsuario();

//Coleta dados padrões de data 
$dadosDatas = CarregarDatasDoUsuario();

//Coleta é armazena dados de registros de usuario
$registrosUsuario = CarregarDatasDoUsuario();

//Configuração de data
$resultadoBuscaMeses = BuscarMeses();

$mes_atual = $resultadoBuscaMeses['mes'];
$ano_atual = $resultadoBuscaMeses['ano'];


?>

<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php include('../../includes/head.html'); ?>

<body>

    <!-- Estrutura Modal-->
    <?php include('../../includes/modal.html'); ?>

    <!-- Navbar -->
    <?php
    include('../../includes/' . $navbar = verificar_permissoes());
    ?>
    <!-- Estrutura da Home -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container">Bem vindo! <?php echo $dados_user['nome'] ?> </h2>
                    </div>
                </div>
                <div class="container-home">
                    <div class="container-welcome welcome-horario">
                        <div class="titulo-container">
                            <h2 class="title-container">Data Atual: <?= $data['data_completa']; ?></h2>
                        </div>
                        <div class="inputs-container">
                            <form action="./settings/conf_data.php" method="post">
                                <div class="container-horarios intem-home">
                                    <!--Envia a data atual para o formulario-->
                                    <input type="hidden" name="dias" value="<?= date('t') ?>">
                                    <input type="hidden" name="mes" value="<?= date('m') ?>">
                                    <input type="hidden" name="ano" value="<?= date('Y') ?>">

                                    <div class="items-horarios">


                                        <?php if ($nome_dia_ingles === "Sunday" || $nome_dia_ingles === "Saturday"): ?>

                                            <div>
                                                <span>Final de Semana</span>
                                            </div>

                                        <?php else: ?>

                                            <input class="horario-input" maxlength="5" type="time"
                                                name="entrada[<?= $dia ?>]"
                                                value="<?= !empty($registroDia['entrada']) ? substr($registroDia['entrada'], 0, 5) : '' ?>"
                                                <?= $existeRegistro ? 'readonly' : '' ?>>
                                        </div>

                                        <div class="items-horarios">
                                            <input class="horario-input" maxlength="5" type="time"
                                                name="saida_pf[<?= $dia ?>]"
                                                value="<?= !empty($registroDia['saida_almoco']) ? substr($registroDia['saida_almoco'], 0, 5) : '' ?>"
                                                <?= $existeRegistro ? 'readonly' : '' ?>>

                                        </div>

                                        <div class="items-horarios">
                                            <input class="horario-input" maxlength="5" type="time"
                                                name="entrada_pf[<?= $dia ?>]"
                                                value="<?= !empty($registroDia['volta_almoco']) ? substr($registroDia['volta_almoco'], 0, 5) : '' ?>"
                                                <?= $existeRegistro ? 'readonly' : '' ?>>
                                        </div>

                                        <div class="items-horarios">
                                            <input class="horario-input" maxlength="5" type="time" name="saida[<?= $dia ?>]"
                                                value="<?= !empty($registroDia['saida']) ? substr($registroDia['saida'], 0, 5) : '' ?>"
                                                <?= $existeRegistro ? 'readonly' : '' ?>>
                                        </div>
                                        <div class="items-horarios">
                                            <button type="submit" name='dia' value=<?= $dia ?>
                                                class="btn-calendario home-calendario">
                                                Enviar datas
                                            </button>

                                        </div>

                                    <?php endif; ?>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="container-card">
                        <div class="card-info">
                            <span class="title-container">Registro Realizados no Mês</span>
                            <span><?= $registrosUsuario['totalMes'] ?> </span>
                            <div class="linha blue"></div>
                        </div>
                        <div class="card-info">
                            <span class="title-container">Registros Completos</span>
                            <?php
                            $total_completo = 0;
                            foreach ($registros as $data => $registro) {
                                [$ano_data, $mes_data, $dia_data] = explode("-", $data);

                                $data_status = $registro['status_dia'];
                                if ($mes_data == $mes_atual && $ano_data === $ano_atual) {
                                    $total_completo++;
                                }
                            }

                            ?>
                            <span> <?= $total_completo ?></span>
                            <div class="linha green"></div>
                        </div>
                        <div class="card-info">
                            <span class="title-container">Registros em Aberto </span>
                            <?php
                            $data_atual = new dateTime("$ano_atual-$mes_atual-01");
                            $ultimoDia = $data_atual->format("t");

                            $total_falta = 0;
                            $dias_validos = 0;

                            for ($dia = 1; $dia < $ultimoDia; $dia++) {
                                $data_format = sprintf('%04d-%02d-%02d', $ano_atual, $mes_atual, $dia);
                                $data_verificar = date('l', strtotime($data_format));
                                if ($data_verificar == "Saturday" || $data_verificar == "Sunday") {
                                    continue;
                                }
                                $dias_validos++;
                            }

                            $total_falta = $dias_validos - $total_mes;
                            ?>

                            <span> <?= $total_falta ?> </span>
                            <div class="linha red"></div>
                        </div>
                    </div>


                    <!-----Cards Inferiores-------->
                    <div class="container-card">
                        <div class="card-inferior">
                            <i class="fa-solid fa-clock card-icon"></i>
                            <span class="title-container">Registrar Ponto</span>
                            <p>Registre seu horário de entrada e saída</p>
                            <div class="btn-container"><a href="registrar-ponto.php"><button button class="btn-cards">Ir
                                        para Folha de Ponto</button></a>
                            </div>
                        </div>
                        <div class="card-inferior">
                            <i class="fa-solid fa-clock card-icon"></i>
                            <span class="title-container">Administrar Horarios</span>
                            <p>Verifique os seu horarios do mês já registrados</p>
                            <div class="btn-container"><input class="btn-cards" type="submit"
                                    value="Ir para Justificativas">
                            </div>
                        </div>
                        <div class="card-inferior">
                            <i class="fa-solid fa-clock card-icon"></i>
                            <span class="title-container">Anexar Frequencia</span>
                            <p>Anexe sua frequencia nos seus registros</p>
                            <div class="btn-container">
                                <a href="./anexar_frequencia.php"><button class="btn-cards">Ir para Anexar
                                        Frequencia</button></a>
                            </div>
                        </div>
                    </div>
        </section>
    </div>
    <!-- Estrutura da script -->
    <?php include('../../includes/script.html') ?>
    </div>

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