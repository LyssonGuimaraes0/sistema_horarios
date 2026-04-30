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

//Configrações de data
$data = mese_atual();
$ano_atual = $data['ano'];
$feriados = feriados($ano_atual);


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
                    <div class="container-welcome welcome-primary">
                        <h1 class="title-container">Gerenciar Feriados</h1>
                    </div>
                </div>
                <div class="container-dropdown">
                    <div class="row-dropdown">
                        <span>Selecione a tarefa:</span>
                        <select class="dropdown" id="dropdown-feriado" style="width:215px;">
                            <option value="" select hidden>Selecione</option>
                            <option value="feriado">Gerenciar Feriado</option>
                            <option value="ponto-facultativo">Gerenciar Ponto Facultativo</option>
                        </select>
                    </div>
                </div>
                <div class="container-feriado">
                    <!-- Página de Feriado -->
                    <?php include("./snippets/feriado.html") ?>

                    <!-- Página de ponto Facultativo -->
                    <?php include("./snippets/ponto_facultativo.html") ?>
                </div>
            </div>

    </div>
    </section>
    </div>



    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>
    </div>

    <script>
        //Coletar feriados para apresentar na lista

        const ListaFeriados = <?php echo json_encode($feriados) ?>;
        const AnoAtual = <?php echo json_encode($ano_atual) ?>;

        //Armazenar feriados do ano no select de escolhas
        function nome_feriados(ListaNomes) {
            const selectNomes = document.querySelector('#nome-feriado')
            const ulNomes = document.querySelector('#lista-feriados')

            //Ajusta datas para verificação
            function converterData(dataBR) {
                const [dia, mes, ano] = dataBR.split("/");
                return new Date(`${ano}-${mes}-${dia}`);
            }

            const arrayDatas = Object.entries(ListaNomes);
            //Organiza lista de Feriados
            arrayDatas.sort((a, b) => converterData(a[0]) - converterData(b[0]))

            arrayDatas.forEach(([data, nome]) => {

                //Cria elemento para lista em Feriados
                const liItem = document.createElement('li')
                liItem.innerText = `${nome} - ${data}`
                ulNomes.appendChild(liItem);


                //Remove para datas registradas com ponto facultativo
                if (nome.includes("Ponto Facultativo")) {
                    return
                }

                //Cria elemento para select em Ponto Facultativo
                const opcao = document.createElement('option')
                opcao.value = nome
                opcao.text = `${nome} - ${data}`
                selectNomes.appendChild(opcao);

            });


        }

        nome_feriados(ListaFeriados);

        //Coleta valor recebido em conf_data.php é armazena
        <?php $cadastro = $_SESSION['cadastro'] ?? null;
        $mensagem = $_SESSION['mensagem'] ?? null;
        $data = $_SESSION['data-encontrada'] ?? null;
        //Limpa valor anteror para novos cadastros!
        unset($_SESSION['cadastro']);
        unset($_SESSION['mensagem']);
        unset($_SESSION['data-encontrada']);
        ?>

        //Apresenta modal caso cadastro tenha falhado ou realizado com sucesso
        var codicao = <?php echo json_encode($cadastro); ?>;
        var mensagem = <?php echo json_encode($mensagem); ?>;
        var data = <?php echo json_encode($data); ?>;

        if (data != null) {
            var mensagemFormatada = `${mensagem}<br> ${data}`
            apresenta_modal(codicao, mensagemFormatada);
        } else {
            apresenta_modal(codicao, mensagem);
        }
    </script>


</body>

</html>