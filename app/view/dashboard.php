<!DOCTYPE html>
<html lang="pt-BR">
<!--Chama Helper Permission--->

<?php use App\helpers\PermissionHelper; ?>

<?php include_once COMPONENTS_PATH . "/head.php"; ?>

<body>

    <!--NavBar-->
    <?php include_once COMPONENTS_PATH . "/navbar.php"; ?>

    <!-- Estrutura da Home -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container">Bem vindo! </h2>
                    </div>
                </div>
                <div class="container-home">
                    <div class="container-welcome welcome-horario">
                        <div class="titulo-container">
                            <h2 class="title-container">Data Atual: <?= date('d/m/Y') ?></h2>
                        </div>
                        <div class="inputs-container">
                            <form action="./settings/conf_data.php" method="post">
                                <div class="container-horarios intem-home">
                                    <!--Envia a data atual para o formulario-->
                                    <input type="hidden" name="dias" value="<?= date('t') ?>">
                                    <input type="hidden" name="mes" value="<?= date('m') ?>">
                                    <input type="hidden" name="ano" value="<?= date('Y') ?>">
                                    <div class="items-horarios">
                                        <!--Apresentação de inputs Baseados em cargos-->

                                        <?php if (PermissionHelper::isServidorPublico($user)): ?>

                                            <div class='input-colunm'>
                                                <span>Entrada</span>
                                                <input class='horario-input' maxlength='5' type='time' name='entrada[$dia]'
                                                    value="">
                                            </div>

                                            <div class='input-colunm'>
                                                <span>Intervalo inicio</span>
                                                <input class='horario-input' maxlength='5' type='time' name='saida_pf[$dia]'
                                                    value="">
                                            </div>

                                            <div class='input-colunm'>
                                                <span>Intervalo volta</span>
                                                <input class='horario-input' maxlength='5' type='time'
                                                    name='entrada_pf[$dia]' value="">
                                            </div>
                                            <div class='input-colunm'>
                                                <span>Saida</span>
                                                <input class='horario-input' maxlength='5' type='time' name='saida[$dia]'>
                                            </div>

                                        <?php endif; ?>


                                        <?php if (PermissionHelper::isEstagiarioManha($user) || PermissionHelper::isEstagiarioTarde($user)): ?>

                                            <div class='input-colunm'>
                                                <span>Entrada</span>
                                                <input class='horario-input' maxlength='5' type='time'
                                                    name='"entrada[$dia]"'>
                                            </div>

                                            <div class='input-colunm'>
                                                <span>Saida</span>
                                                <input class='horario-input' maxlength='5' type='time' name='saida[$dia]'>
                                            </div>

                                        <?php endif; ?>

                                        <div class="items-botoes">
                                            <button type='submit' class='btn-calendario' name='dia'
                                                value='$dia'>Confirmar</button>
                                            <i class='fa-solid fa-file-alt botao-calendario'
                                                onclick=\"adicionar_justificativa('$data_str')\"></i>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="container-card">
                        <!--Apresentação de inputs Baseados em cargos-->
                        <?php if (!PermissionHelper::isCoordenador($user) || !PermissionHelper::isPPE($user)): ?>
                            <div class="card-info"> <span class="title-container">Registro Realizados no Mês</span>
                                <span></span>
                                <div class="linha blue"></div>
                            </div>
                            <div class="card-info">
                                <span class="title-container">Registros Completos</span>
                                <span></span>
                                <div class="linha green"></div>
                            </div>
                            <div class="card-info">
                                <span class="title-container">Registros em Aberto </span>
                                <span> </span>
                                <div class="linha red"></div>
                            </div>
                        <?php endif; ?>

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

    <script type="module" src="public/assets/js/page/dashboard.js"></script>

</body>

</html>