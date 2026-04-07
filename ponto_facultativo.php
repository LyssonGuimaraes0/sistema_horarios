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
                    <form action="./settings/registrar_ponto_facultativo.php" method="post"
                        enctype="multipart/form-data">
                        <div class="container-ponto-facultativo">
                            <div class="calendario-header">

                                <div class="calendario-titulo">
                                    <span>Configurações de Ponto Facultativo:</span>
                                </div>
                            </div>
                            <div class="container-upload">
                                <div class="container-input">
                                    <span>Selecione o data de compensação</span>
                                    <div class="item-horario">
                                        <div class="item-upload">
                                            <span>Data de Inicio</span>
                                            <input type="date" class="horario-input" id="horairo-inicio"
                                                name="inicio-ponto-facultativo[0]" required>
                                        </div>
                                        <div class="item-upload">
                                            <span>Data de Fim</span>
                                            <input type="date" class="horario-input" id="horairo-fim"
                                                name="fim-ponto-facultativo[0]" required>
                                        </div>
                                    </div>
                                    <div class="item-horario ">
                                        <div class="items-horarios">
                                            <span>Entrada</span>
                                            <input class="horario-input" maxlength="5" type="time" id="input-entrada"
                                                name="entrada[0]" required>
                                        </div>
                                        <div class="items-horarios">
                                            <span>Intervalo inicio</span>
                                            <input class="horario-input" maxlength="5" type="time"
                                                id="input-saida-almoco" name="saida_almoco[0]" required>
                                        </div>
                                        <div class="items-horarios">
                                            <span>Intervalo volta</span>
                                            <input class="horario-input" maxlength="5" type="time"
                                                id="input-volta-almoco" name="volta_almoco[0]" required>
                                        </div>
                                        <div class="items-horarios">
                                            <span>Saida</span>
                                            <input class="horario-input" maxlength="5" type="time" id="input-saida"
                                                name="saida[0]" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="items-botoes">
                                    <button class="btn-calendario adicionar-div" type="button"><i
                                            class="fa-solid fa-plus"></i></button>
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