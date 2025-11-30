<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
session_start();
//Configura um tempo de inatividade para desconectar!
time_out();
//Verifica permissao do ususario atual
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
                    <h2 class="title-container">Bem vindo! <?php echo $dados_user['nome'] ?> </h2>
                </div>
            </div>
        </div>

    </section>

    <!-- Estrutura da Página de adicionar usuario -->
    <?php include('./snippets/adicionar_user.html') ?>

    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>

    <script>
        //Coleta valor recebido em conf_cadastro.php é armazena
        <?php $cadastro = $_SESSION['cadastro'] ?? null;
        //Limpa valor anterior para novos cadastros!
        unset($_SESSION['cadastro']);

        ?>
        //Apresenta modal caso cadastro tenha falhado ou realizado com sucesso
        var VerificarCadastro = <?php echo json_encode($cadastro); ?>;

        switch (VerificarCadastro) {
            //usuario cadastrado com sucesso
            case "cadastrado":

                apresenta_modal('modal-cad-sucesso', 'btn-cad-sucesso');

                break;
            //usuario nao foi cadastrado
            case "falha":

                apresenta_modal('modal-cad-falha', 'btn-cad-falha');

                break;

            case "usuario ja cadastrado":

                apresenta_modal('modal-cpf', 'btn-cpf');

                break;

            default:
                break;
        }

    </script>



</body>



</html>