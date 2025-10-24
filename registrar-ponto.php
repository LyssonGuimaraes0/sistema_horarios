<?php
session_start();
//Chamada das configurações do banco de dados e logout
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
$conn = conexao_banco();

if ($_SESSION['user_id'] == null) {
    header("location: index.php");
    exit();
}

time_out();
$dados_user = dados_user()


?>

<!DOCTYPE html>
<html lang="pt-br">
<!-- Cabeçalho comum incluído -->
<?php include('./snippets/head.html'); ?>

<body>

    <!-- Cabeçalho comum incluído -->
    <?php include('./snippets/navbar.html'); ?>

    <main class="main-content">
    <!-- Dados usuario -->
    <?php include('./snippets/dados-user.php'); ?>





            <!-- Chamada dos Scripts -->
            <?php include('./snippets/script.html'); ?>
    </main>