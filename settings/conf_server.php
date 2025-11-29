<?php
function time_out()
{
    //time out de logout por inatividade
    $timeout_duration = 1300; //3600; // 60 minutos <- Ajusta caso necessário

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

//Função de erro em login onde passa para o
function error_login()
{
    $error_login = $_SESSION['error_login'] ?? false;
    unset($_SESSION['error_login']);
}

function dados_user()
{

    $conn = conexao_banco();

    $usuario_id = $_SESSION['user_id'];

    $query = $conn->prepare("SELECT * FROM usuario WHERE id = ?");

    $query->bind_param("s", $usuario_id);

    $query->execute();
    $resultado = $query->get_result();
    $dados_usuario = $resultado->fetch_assoc();

    return $dados_usuario;

}

//Verifica Permissoes do usuario

//entra um array e verifica se possui a permissão necessaria
function verificar_permissoes($array)
{
    if ($array['permissoes'] == "administrador") {
        return true;

    } else {
        return false;
    }
}


?>