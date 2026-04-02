<!DOCTYPE html>
<html lang="pt-BR">
<!-- Cabeçalho comum incluído -->
<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();
$dados_user = dados_user();
$setores = setores();
$usuarios_coletados = coletar_user();
limparFiltros();
//Verifica permissão do usuario, caso não possua retorna
if (verificar_permissoes($dados_user) !== true) {
    header("location: ./home.php");
    exit;
}


?>
<?php include('./snippets/head.html'); ?>

<body>

    <!-- Estrutura Modal-->
    <?php include('./snippets/modal.html'); ?>

    <!-- Navbar -->
    <?php include('./snippets/navbar-admin.html'); ?>

    <!--Banner Superior Home-->
    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container">Ponto Facultativo</h2>
                    </div>
                </div>
                <!--Formulario Para ponto facultativo-->

                <div class="container-home">
                    <form action="./settings/registrar_ponto_facultativo.php" method="post" enctype="multipart/form-data">
                        <div class="container-anexar-frequencia">
                            <div class="calendario-header">

                                <div class="calendario-titulo">
                                    <span>Selecione a data correspondente:</span>
                                </div>
                            </div>
                            <div class="container-upload">
                                <div class="item-upload">
                                    <!--Coleta os o arquivo e o periodo selecionado pelo usuario-->
                                    <input type="date" class="horario-input" name="ponto-facultativo" required>
                                    <button class="btn-calendario" type="submit">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </form>
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