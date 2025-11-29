<!DOCTYPE html>
<html lang="pt_BR">
<!-- Cabeçalho comum incluído -->
<?php
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
session_start();
//Configura um tempo de inatividade para desconectar!
time_out();
$dados_user = dados_user()

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
    <!-- Estrutura da Home -->
    <section class="home-section">
        <div class="section-container">
            <div class="container-home">
                <div class="container-welcome">
                    <h2 class="title-container">Bem vindo! <?php echo $dados_user['nome'] ?> </h2>
                </div>
            </div>
            <div class="container-card">
                <div class="card-info">
                    <span class="title-container">Registro Este Mês</span>
                    <p> - </p>
                    <div class="linha"></div>
                </div>
                <div class="card-info">
                    <span class="title-container">Presentes <?php echo $dados_user['nome'] ?> </span>
                </div>
                <div class="card-info">
                    <span class="title-container">Faltas <?php echo $dados_user['nome'] ?> </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>

</body>

</html>