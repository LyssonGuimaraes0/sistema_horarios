<?php

define('BASE_URL', '/sistema-horarios/public/');


function iniciar_sessao()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

//Time out por inativiade

function time_out()
{
    iniciar_sessao();
    //time out de logout por inatividade
    $timeout_duration = 1300; //3600; // 60 minutos <- Ajusta caso necessário

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
        // Destroi a sessão após o timeout
        session_unset();
        session_destroy();
        if (defined('AJAX')) {
            http_response_code(401);
            echo json_encode(['status' => 'timeout']);
            exit;
        }
        header("Location: ./index.php");
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['LAST_ACTIVITY'] = time();
}

//Limpar variaveis de sessão caso outra pagina seja acessada

function limparFiltros()
{
    if (basename($_SERVER['PHP_SELF']) !== 'buscar_usuario.php') {
        unset(
            $_SESSION['usuario_selecionado'],
            $_SESSION['setor_selecionado'],
            $_SESSION['ano_selecionado'],
            $_SESSION['formulario_exibido'],
            $_SESSION['ano_selecionado'],
            $_SESSION['mes_selecionado'],
            $_SESSION['exibir_formulario_anexo']

        );
    }
}
 

?>