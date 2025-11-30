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
                    <span> - </span>
                    <div class="linha blue"></div>
                </div>
                <div class="card-info">
                    <span class="title-container">Presentes </span>
                    <span> <?php echo $dados_user['nome'] ?> </span>
                    <div class="linha green"></div>
                </div>
                <div class="card-info">
                    <span class="title-container">Faltas </span>
                    <span> <?php echo $dados_user['nome'] ?> </span>
                    <div class="linha red"></div>
                </div>
            </div>
            <!-----Cards Inferiores-------->
            <div class="container-card">
                <div class="card-inferior">
                    <i class="fa-solid fa-clock card-icon"></i>
                    <span class="title-container">Registrar Ponto</span>
                    <p>Registre seu horário de entrada e saída</p>
                    <div class="btn-container"><input class="btn-cards" type="submit" value="Ir para Folha de Ponto">
                    </div>
                </div>
                <div class="card-inferior">
                    <i class="fa-solid fa-clock card-icon"></i>
                    <span class="title-container">Administrar Horarios</span>
                    <p>Verifique os seu horarios do mês já registrados</p>
                    <div class="btn-container"><input class="btn-cards" type="submit" value="Ir para Justificativas">
                    </div>
                </div>
                <div class="card-inferior">
                    <i class="fa-solid fa-clock card-icon"></i>
                    <span class="title-container">Imprimir Frequencia</span>
                    <p>Imprima seu registro para assinatura do Coordenador</p>
                    <div class="btn-container"><input class="btn-cards" type="submit"
                            value="Ir para Imprimir Frequencia"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estrutura da script -->
    <?php include('./snippets/script.html') ?>

</body>

</html>