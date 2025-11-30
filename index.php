<!DOCTYPE html>
<html lang="pt-br">
<!-- Cabeçalho comum incluído -->
<?php include('./settings/conf_server.php'); ?>
<!--Verifica caso teve erro no Login-->
<?php error_login() ?>
<?php session_start();
$error_login = $_SESSION['error_login'] ?? false;
unset($_SESSION['error_login']); // remove após usar
?>
<?php include('./snippets/head.html'); ?>

<body>
    <main>
        <!-- Pagina Login -->
        <?php include('./snippets/login.html'); ?>

        <?php include('./snippets/modal.html'); ?>
        <!-- Chamada Script-->

        <?php include('./snippets/script.html') ?>
    </main>
    <script>
        //Configuração de Tela de erro ao tenta realizar Login
            var error_login = <?php echo json_encode($error_login); ?>;
            apresenta_modal('modal-login','btn-login',error_login);
    </script>

</body>

</html>