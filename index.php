<!DOCTYPE html>
<html lang="pt-br">
<!-- Cabeçalho comum incluído -->
<?php include('./settings/conf_server.php'); ?>
<!--Verifica caso teve erro no Login-->
<?php 
error_login();
session_start();
$login = $_SESSION['error_login'] ?? null;
$mensagem = $_SESSION['mensagem'] ?? null;
unset($_SESSION['error_login']); // remove após usar
unset($_SESSION['mensagem']);
?>
<?php include('./snippets/head.html'); ?>

<body>
    <main>
        <!-- Pagina Login -->
        <?php include('./snippets/login.html'); ?>

        <?php include('./snippets/modal.html'); ?>
        <!-- Chamada Script-->

        <?php include('./snippets/script.html'); ?>
    </main>
    <script>
        //Configuração de Tela de erro ao tenta realizar Login
            var error_login = <?php echo json_encode($login); ?>;
            var mensagem = <?php echo json_encode($mensagem); ?>;
            apresenta_modal(error_login,mensagem);
            console.log(error_login);
    </script>

</body>

</html>