
<?php
function time_out(){
//time out de logout por inatividade
    $timeout_duration = 3600; //3600; // 60 minutos <- Ajusta caso necessário

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
        // Destroi a sessão após o timeout
        session_unset();
        session_destroy();
        header("Location: ./index.php");
        exit();
    }

    // Atualiza o tempo da última atividade
    $_SESSION['LAST_ACTIVITY'] = time();
}
?>