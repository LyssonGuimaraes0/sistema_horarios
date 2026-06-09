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

    <!-- Estrutura da Registro de Horarios -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-white">
                        <h1 class="title-container">Registro de Horario </h1>
                    </div>
                </div>
                <div class="section-container">
                    <div class="container-home container-down">
                        <div class="container-dropdown">
                            <div class="row-dropdown">
                                <span>Selecione um periodo:</span>
                                <div>
                                    <span> Mês:</span>

                                    <select class="dropdown" name="mes" id="selectMes">
                                    </select>

                                    <span> Ano:</span>
                                    <select class="dropdown" name="ano" id="selectAno">
                                    </select>

                                    <button class="btn-formulario btn-registrar" id="abrir-calendario">Carregar</button>
                                </div>
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
        <section class="home-section home-up">
            <div class="section-container">
                <!--Calendario Oculto-->
                <div class="container-calendario calendario-container" id="calendario-form">
                    <div class="calendario-header">
                        <div class="calendario-titulo">
                            <span>Folha de Ponto</span>
                        </div>
                    </div>
                    <!--Folha de horario--->
                    <div class="calendario-body" >
                        <form id="attendance-form">
                            <!--NavBar-->
                            <?php include_once COMPONENTS_PATH . "/card-attendance.php"; ?>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="module" src=<?= SCRIPT_URL . "/page/attendance.js" ?>></script>

</body>

</html>