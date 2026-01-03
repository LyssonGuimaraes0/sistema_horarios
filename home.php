<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();

$dados_user = dados_user();

//Configuração de data
$data = mese_atual();

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
                        <h2 class="title-container">Bem vindo! <?php echo $dados_user['nome'] ?> </h2>
                    </div>
                </div>
                <div class="container-home">
                    <div class="container-welcome welcome-horario">
                        <div class="titulo-container">
                            <h2 class="title-container">Data Atual: <?= $data_completa = $data['data_completa']; ?></h2>
                        </div>
                        <div class="inputs-container">
                            <form action="./settings/conf_data.php" method="post">
                                <?php
                                //Função para verificar horario ja registrando no banco
                                $datas_registradas = horas_registradas($_SESSION['user_id']);

                                //formata para estilo do banco
                                $data_str = date('Y-m-d');
                                $registroDia = $datas_registradas[$data_str] ?? null;
                                $existeRegistro = isset($datas_registradas[$data_str]);
                                $dia = date('j');
                                //Coleta nome do Dia
                                $nome_dia_ingles = date('l', strtotime($data_str));

                                ?>
                                <div class="container-horarios intem-home">
                                    <!--Envia a data atual para o formulario-->
                                    <input type="hidden" name="dias" value="<?= date('t') ?>">
                                    <input type="hidden" name="mes" value="<?= date('m') ?>">
                                    <input type="hidden" name="ano" value="<?= date('Y') ?>">

                                    <div class="items-horarios">
                                        <?php

                                        if ($nome_dia_ingles === "Sunday" || $nome_dia_ingles === "Saturday") {

                                            echo "<div>
                                                    <span>Final de Semana</span>
                                                    </div>";
                                        } else {

                                        ?>
                                            <input
                                                class="horario-input"
                                                maxlength="5"
                                                type="time"
                                                name="entrada[<?= $dia ?>]"
                                                value="<?= !empty($registroDia['entrada']) ? substr($registroDia['entrada'], 0, 5) : '' ?>"
                                                <?= $existeRegistro ? 'readonly' : '' ?>>
                                    </div>

                                    <div class="items-horarios">
                                        <input
                                            class="horario-input"
                                            maxlength="5"
                                            type="time"
                                            name="saida_pf[<?= $dia ?>]"
                                            value="<?= !empty($registroDia['saida_almoco']) ? substr($registroDia['saida_almoco'], 0, 5) : '' ?>"
                                            <?= $existeRegistro ? 'readonly' : '' ?>>

                                    </div>

                                    <div class="items-horarios">
                                        <input
                                            class="horario-input"
                                            maxlength="5"
                                            type="time"
                                            name="entrada_pf[<?= $dia ?>]"
                                            value="<?= !empty($registroDia['volta_almoco']) ? substr($registroDia['volta_almoco'], 0, 5) : '' ?>"
                                            <?= $existeRegistro ? 'readonly' : '' ?>>
                                    </div>

                                    <div class="items-horarios">
                                        <input
                                            class="horario-input"
                                            maxlength="5"
                                            type="time"
                                            name="saida[<?= $dia ?>]"
                                            value="<?= !empty($registroDia['saida']) ? substr($registroDia['saida'], 0, 5) : '' ?>"
                                            <?= $existeRegistro ? 'readonly' : '' ?>>
                                    </div>
                                    <div class="items-horarios">

                                        <?php if ($existeRegistro): ?>
                                            <div class="items-botoes">
                                                <i class="fa-solid fa-pen-to-square botao-calendario"></i>
                                                <button type="submit" class="btn-calendario home-calendario">
                                                    Enviar datas
                                                </button>
                                            </div>
                                        <?php endif; ?>

                                    <?php } ?>
                                    </div>

                                </div>

                        </div>


                        </form>
                    </div>
                </div>
                <div class="container-card">
                    <div class="card-info">
                        <span class="title-container">Registro Realizados esse Mês</span>
                        <span> - </span>
                        <div class="linha blue"></div>
                    </div>
                    <div class="card-info">
                        <span class="title-container">Presentes </span>
                        <span> <?php echo $dados_user['nome'] ?> </span>
                        <div class="linha green"></div>
                    </div>
                    <div class="card-info">
                        <span class="title-container">Faltas </span>
                        <span> <?php echo $dados_user['nome'] ?> </span>
                        <div class="linha red"></div>
                    </div>
                </div>


                <!-----Cards Inferiores-------->
                <div class="container-card">
                    <div class="card-inferior">
                        <i class="fa-solid fa-clock card-icon"></i>
                        <span class="title-container">Registrar Ponto</span>
                        <p>Registre seu horário de entrada e saída</p>
                        <div class="btn-container"><a href="registrar-ponto.php"><button button class="btn-cards">Ir para Folha de Ponto</button></a>
                        </div>
                    </div>
                    <div class="card-inferior">
                        <i class="fa-solid fa-clock card-icon"></i>
                        <span class="title-container">Administrar Horarios</span>
                        <p>Verifique os seu horarios do mês já registrados</p>
                        <div class="btn-container"><input class="btn-cards" type="submit" value="Ir para Justificativas">
                        </div>
                    </div>
                    <div class="card-inferior">
                        <i class="fa-solid fa-clock card-icon"></i>
                        <span class="title-container">Imprimir Frequencia</span>
                        <p>Imprima seu registro para assinatura do Coordenador</p>
                        <div class="btn-container"><input class="btn-cards" type="submit"
                                value="Ir para Imprimir Frequencia"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>
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