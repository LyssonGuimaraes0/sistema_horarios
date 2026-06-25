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
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <section class="home-section home-up">
            <div class="section-container">
                <!--Calendario Oculto-->
                <div class="container-calendario calendario-container" id="calendario-form">
                    <div class="loading-overlay hidden"></div>
                    <div class="calendario-header">
                        <div class="calendario-titulo">
                            <span>Folha de Ponto</span>
                        </div>
                        <div class="calendario-header-btns">
                            <button class="btn-formulario-header" id="btn-anexar-frequencia">
                                <i class="fa-solid fa-upload card-icon"></i>
                                <span>Anexar Frequência</span>
                            </button>
                            <button class="btn-formulario-header" id="btn-gerar-frequencia">
                                <i class="fa-solid fa-print card-icon"></i>
                                <span>Gerar Frequencia</span>
                            </button>
                        </div>
                    </div>
                    <!--Folha de horario--->
                    <div class="calendario-body">
                        <form id="attendance-form">
                            <!--Cards-->
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