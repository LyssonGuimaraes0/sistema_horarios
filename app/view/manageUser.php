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

    <!-- Estrutura da Página de Gerenciamento de usuario -->

    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome welcome-white">
                        <h1 class="title-container">Buscar usuario </h1>
                    </div>
                </div>
                <div class="container-home container-down">
                    <div class="container-dropdown">
                        <div class="row-dropdown">
                            <span>Selecione um setor:</span>
                            <select class="dropdown" name="setor" id="dropdown-setor">
                                <option value="" selected hidden>Selecione Um setor</option>
                            </select>
                        </div>
                    </div>

                    <div class="container-dropdown " id="container-pessoas">
                        <div class="row-dropdown">
                            <span>Selecione uma pessoa:</span>
                            <select class="dropdown" name="pessoa-seletor" id="dropdown-pessoas"></select>
                            <option value="" selected hidden>Selecione o usuario</option>
                            <button class="btn-formulario btn-registrar" type="submit">Buscar</button>
                        </div>
                    </div>
                </div>
                <!-- Container de dados do usuario -->



            </div>
        </section>
    </div>

    <script type="module" src=<?= SCRIPT_URL . "/page/manageUser.js" ?>></script>

</body>

</html>