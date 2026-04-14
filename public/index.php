<?php 

require_once dirname(__DIR__) . '/settings/config.php';


require CONTROLLER_PATH .'/LoginController.php';
require HELPER_PATH . '/SessionHelper.php';
require HELPER_PATH . '/ModalHelper.php';

iniciar_sessao();

$resposta = VerificarRespostaModal();

//Verifica login do Usuario
login();

?>

<!DOCTYPE html>
<html lang="pt-br">
<!-- Cabeçalho comum incluído -->
<?php include('../settings/conf_server.php'); ?>
<!--Verifica caso teve erro no Login-->
<?php 


?>
<?php include('../includes/head.php'); ?>

<body>
    <main>
        <!-- Pagina Login -->
        <?php include('../includes/login.html'); ?>

        <?php include('../includes/modal.html'); ?>
        <!-- Chamada Script-->

        <?php include('../includes/script.html'); ?>
    </main>
    <script>
        //Configuração de Tela de erro ao tenta realizar Login
            var error_login = <?php echo json_encode($resposta['codigo']); ?>;
            var mensagem = <?php echo json_encode($resposta['mensagem']); ?>;
            apresenta_modal(error_login,mensagem);
    </script>

</body>

</html>