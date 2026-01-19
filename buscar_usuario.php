<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
error_reporting(E_ALL & ~E_WARNING);
ini_set('display_errors', 0);
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();
limparFiltros();

$dados_user = dados_user();
$setores = setores();
$usuarios_coletados = coletar_user();

// ===============================
// USUÁRIO
// ===============================
if (isset($_POST['pessoa-seletor'])) {

    $usuarioAnterior = $_SESSION['usuario_selecionado'] ?? null;
    $usuarioAtual = $_POST['pessoa-seletor'];

    $_SESSION['usuario_selecionado'] = $usuarioAtual;

    // se trocou o usuário, limpa dependências
    if ($usuarioAnterior !== null && $usuarioAnterior != $usuarioAtual) {
        $_POST['ano-seletor'] = '';
    }
}

// ===============================
// SETOR
// ===============================
if (isset($_POST['setor'])) {
    $_SESSION['setor_selecionado'] = $_POST['setor'];
}

// ===============================
// ANO
// ===============================
if (isset($_POST['ano-seletor'])) {
    $_SESSION['ano_selecionado'] = $_POST['ano-seletor'];
}


//Valores coletados dos inputs dos usuarios

$usuario_selecionado = $_SESSION['usuario_selecionado'] ?? '';
$setor_selecionado   = $_SESSION['setor_selecionado'] ?? '';
$ano_selecionado = $_SESSION['ano_selecionado'] ?? null;


// Valores coletados dos inputs dos usuários
$usuario_selecionado = $_SESSION['usuario_selecionado'] ?? '';
$setor_selecionado   = $_SESSION['setor_selecionado'] ?? '';
$ano_selecionado     = $_SESSION['ano_selecionado'] ?? null;


$data = mese_atual();
$meses = $data['meses'];
$mes_atual = $data['mes'];
$ano_atual = $data['ano'];
$data_completa = $data['data_completa'];
$anolimite = $data['ano_limite'];
$anolimite = (int) $anolimite;


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

    <!-- Estrutura da Página -->
    <div class="main-content">
        <section class="home-section">
            <div class="section-container">
                <div class="container-home">
                    <div class="container-welcome">
                        <h2 class="title-container">Buscar usuario </h2>
                    </div>
                </div>
                <div class="container-home">
                    <form method="post" autocomplete="off">

                        <div class="container-dropdown">
                            <div class="row-dropdown">
                                <span>Selecione um setor:</span>
                                <select class="dropdown" name="setor" id="dropdown-setor">
                                    <option value="" disabled hidden <?= ($setor_selecionado === "") ? 'selected' : '' ?>>
                                        Selecione um setor
                                    </option>

                                    <?php foreach ($setores as $setor): ?>
                                        <option value="<?= $setor ?>" <?= ($setor_selecionado === $setor) ? 'selected' : '' ?>>
                                            <?= $setor ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="container-dropdown" id="container-pessoas"
                            style="display:<?= ($usuario_selecionado != '') ? 'flex' : 'none' ?>">
                            <div class="row-dropdown">
                                <span>Selecione uma pessoa:</span>
                                <select class="dropdown" name="pessoa-seletor" id="dropdown-pessoas"></select>
                                <button class="btn-formulario btn-registrar" type="submit">Buscar</button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="container-home">
                    <div style="display:<?= ($usuario_selecionado != '') ? 'block' : 'none' ?>">
                        <div class="container-calendario calendario-container">
                            <div class="calendario-header">
                                <?php
                                $usuario_ficha = [];
                                $usuario_selecionado = (int) $usuario_selecionado;
                                foreach ($usuarios_coletados as $usuariobusca) {
                                    if ($usuariobusca['id'] === $usuario_selecionado) {
                                        $usuario_ficha = $usuariobusca;
                                        break;
                                    }
                                }

                                ?>
                                <div class="calendario-titulo">
                                    <span>Ficha de <?= $usuario_ficha['nome'] ?></span>
                                </div>
                            </div>
                            <div class="calendario-body">
                                <span>Dados do Perfil:</span>
                                <table>
                                    <thead>
                                        <th>Email:</th>
                                        <th>CPF</th>
                                        <th>Permissões</th>
                                        <th>Nome de acesso</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= $usuario_ficha['email'] ?></td>
                                            <td><?= $usuario_ficha['cpf'] ?></td>
                                            <td><?= $usuario_ficha['permissoes'] ?></td>
                                            <td><?= $usuario_ficha['username'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!--Accordion-->
                                <div class="accordion">
                                    <div class="accordion-item <?= ($ano_selecionado != '') ? "active" : ''  ?>">
                                        <button class="accordion-header">Folha de Ponto mensal</button>
                                        <div class="accordion-content">
                                            <div class="row-dropdown">
                                                <span>Selecione o Ano:</span>
                                                <form action="" method="post">
                                                    <select class="dropdown" name="ano-seletor">
                                                        <?php
                                                        for ($ano = $ano_atual; $ano >= $anolimite; $ano--) {
                                                            echo "<option value='$ano'" . (($ano_selecionado == $ano) ? 'selected' : '') . ">$ano</option>";
                                                        }

                                                        ?>
                                                    </select>
                                                    <button class="btn-formulario btn-registrar" type="submit">Buscar</button>

                                                </form>
                                            </div>
                                            <table style="display:<?= ($ano_selecionado != '') ? 'inline-table' : 'none' ?>">
                                                <thead>
                                                    <th>Mes/Ano:</th>
                                                    <th>Nome do arquivo</th>
                                                    <th>Verificar Arquivo</th>
                                                    <th>Validação</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    //Verificar mes
                                                    for ($mes = 12; $mes >= 1; $mes--) {

                                                        $formata_mes = str_pad($mes, 2, '0', STR_PAD_LEFT);

                                                        //busca folha de ponto do usuario

                                                        $registros_folha = folha_ponto_registro($usuario_ficha['id'], $formata_mes, $ano_selecionado);

                                                        if ($formata_mes != $mes_atual && $ano_selecionado == $ano_atual) {
                                                            continue;
                                                        }
                                                        //Coleta dados do mes atual
                                                        if (isset($registros_folha[$formata_mes])) {
                                                            $caminho_arquivo = $registros_folha[$formata_mes]['caminho_folha_de_ponto'];
                                                        } else {
                                                            $caminho_arquivo = '';
                                                        }
                                                        //formata nome do arquivo




                                                        if (preg_match('/(folha_ponto_id.+)$/', $caminho_arquivo, $nome_arquivo)) {
                                                        }

                                                        $nome_mes = $meses[$formata_mes];

                                                        //cria Tabela com valores coletados

                                                        echo "<tr>";
                                                        echo "<td>$nome_mes/$ano_selecionado</td>";
                                                        if ($caminho_arquivo === '' ) {
                                                            echo "<td>Folha não Registrada</td>";
                                                            echo "<td></td>";
                                                            echo "<td></td>";
                                                            echo "</tr>";
                                                            continue;
                                                        }
                                                        echo "<td>$nome_arquivo[1]</td>";
                                                        echo "<td><a href='$caminho_arquivo' target='_blank'>Ver</a></td>";
                                                        echo "<td></td>";
                                                        echo "</tr>";
                                                    }

                                                    ?>
                                                </tbody>




                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



        </section>
    </div>
    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const usuarios_coletados = <?= json_encode($usuarios_coletados); ?>;
            const setorSelecionadoPHP = <?= json_encode($setor_selecionado); ?>;
            const usuarioSelecionadoPHP = <?= json_encode($usuario_selecionado); ?>;

            const selectSetor = document.getElementById('dropdown-setor');
            const selectPessoa = document.getElementById('dropdown-pessoas');
            const containerpessoas = document.getElementById('container-pessoas');

            function carregarUsuarios(setor) {
                selectPessoa.innerHTML = "";

                if (!setor) return;

                containerpessoas.style.display = "flex";

                usuarios_coletados.forEach(usuario => {
                    if (usuario.setor === setor) {
                        const option = document.createElement("option");
                        option.value = usuario.id;
                        option.text = usuario.nome;

                        if (usuario.id == usuarioSelecionadoPHP) {
                            option.selected = true;
                        }

                        selectPessoa.appendChild(option);
                    }
                });
            }

            if (setorSelecionadoPHP) {
                carregarUsuarios(setorSelecionadoPHP);
            }

            selectSetor.addEventListener('change', function() {
                carregarUsuarios(this.value);
            });

        });




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