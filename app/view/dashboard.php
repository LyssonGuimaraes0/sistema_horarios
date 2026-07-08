<!DOCTYPE html>
<html lang="pt-BR">
<!--Chama Helper Permission--->

<?php use App\helpers\PermissionHelper; ?>

<?php include_once COMPONENTS_PATH . "/head.php"; ?>

<body>

    <!--NavBar-->
    <?php include_once COMPONENTS_PATH . "/navbar.php"; ?>

    <!--Modal-->
    <?php include_once COMPONENTS_PATH . "/modal.php"; ?>

    <!-- Estrutura da Home -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container" id="home-nameUser">Bem vindo! <?= $dataUser['nome'] ?></h2>
                    </div>
                </div>
                <div class="container-home">
                    <div class="container-welcome welcome-horario">
                        <div class="titulo-container">
                            <h2 class="title-container">Data Atual: <?= (new DateTime($record['currentday']))->format('d/m/Y') ?></h2>
                        </div>
                        <div class="inputs-container">
                            <form id="form-record-dashboard">
                                <div class="container-horarios intem-home" data-date=<?= $record['currentday'] ?>>
                                    <!--Envia a data atual para o formulario-->
                                    <div class="items-horarios">
                                        <!--Apresentação de inputs Baseados em cargos-->

                                        <div class='input-colunm'>
                                            <span>Entrada</span>
                                            <input class='horario-input' step="60" type='time' name='entrada'
                                                value="<?= $record['entrada']?>">
                                        </div>
                                        <?php if (PermissionHelper::isServidorPublico($user)): ?>
                                            <div class='input-colunm'>
                                                <span>Intervalo inicio</span>
                                                <input class='horario-input' step="60" type='time' name='saida_almoco'
                                                    value="<?= $record['saida_almoco']?>">
                                            </div>

                                            <div class='input-colunm'>
                                                <span>Intervalo volta</span>
                                                <input class='horario-input' step="60" type='time'
                                                    name='volta_almoco' value="<?= $record['volta_almoco']?>">
                                            </div>

                                        <?php endif; ?>

                                        <div class='input-colunm'>
                                            <span>Saida</span>
                                            <input class='horario-input' step="60" type='time' name='saida' value="<?= $record['saida']?>">
                                        </div>

                                        <div class="items-botoes">
                                            <button type='submit' class='btn-calendario'>Confirmar</button>
                                            <i class='fa-solid fa-file-alt botao-calendario' data-action="certificate"></i>
                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="container-card">
                        <div class="card-info"> <span class="title-container">Registro Realizados no Mês</span>
                            <span><?= $record['registereddays'] ?></span>
                            <div class="linha blue"></div>
                        </div>
                        <div class="card-info">
                            <span class="title-container">Registros em Aberto</span>
                            <span><?= $record['pendingdays'] ?></span>
                            <div class="linha green"></div>
                        </div>
                        <div class="card-info">
                            <span class="title-container">Feriados/Pontos Facultativos</span>
                            <span><?= $record['holidays'] ?></span>
                            <div class="linha red"></div>
                        </div>

                    </div>

                    <!-----Cards Inferiores-------->
                    <!-- <div class="container-card">
                        <div class="card-inferior">
                            <i class="fa-solid fa-clock card-icon"></i>
                            <span class="title-container">Registrar Ponto</span>
                            <p>Registre seu horário de entrada e saída</p>
                            <div class="btn-container"><button id="btn-teste" class="btn-cards">Ir
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
                    </div> -->
        </section>
    </div>

    <script type="module" src=<?= SCRIPT_URL . "/page/dashboard.js" ?>></script>

</body>

</html>