<!DOCTYPE html>
<html lang="pt-BR">
<?php
session_start();
include('./settings/conf_bd.php');
include('./settings/conf_server.php');
verificar_sessao();
limparFiltros();
$dados_user = dados_user();

include('./snippets/head.html'); ?>

<body>
    <!-- Navbar -->
    <?php if (verificar_permissoes($dados_user) == true) {
        include('./snippets/navbar-admin.html');
    } else {
        include('./snippets/navbar.html');
    }
    ?>

</body>

</html>