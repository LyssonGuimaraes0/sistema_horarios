<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();
limparFiltros();
$dados_user = dados_user();

if (verificar_permissoes($dados_user) !== true) {
    header("location: ./home.php");
    exit;
}

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

    <!-- Estrutura Modal-->
    <?php include('./snippets/modal.html'); ?>


    <!-- Estrutura da Home -->
    <section class="home-section">
        <div class="section-container">
            <div class="container-home">
                <div class="container-welcome">
                    <h2 class="title-container">Cadastro de novo Úsuario</h2>
                </div>
            </div>
        </div>
    </section>

    <!-- Estrutura da Página de adicionar usuario -->
    <?php include('./snippets/adicionar_user.html') ?>

    <!-- Chamada Script-->

    <?php include('./snippets/script.html') ?>
    <script>
        //Coleta valor recebido em conf_cadastro.php é armazena
        <?php $cadastro = $_SESSION['cadastro'] ?? null;
              $mensagem = $_SESSION['mensagem'] ?? null;
        //Limpa valor anterior para novos cadastros!
        unset($_SESSION['cadastro']);
        unset($_SESSION['mensagem']);
        ?>
        //Apresenta modal caso cadastro tenha falhado ou realizado com sucesso
        var codicao = <?php echo json_encode($cadastro); ?>;
        var mensagem = <?php echo json_encode($mensagem); ?>;

        apresenta_modal(codicao,mensagem);


    </script>



</body>



</html>