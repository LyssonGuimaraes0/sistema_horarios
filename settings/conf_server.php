<?php
//Configurações de Datas do Servidor :(
date_default_timezone_set('America/Sao_Paulo');


//verifica mes correspondente
function meses($mes)
{
    //valor do mes atual
    $mes_atual_numero = date("m");

    $meses = [
        1  => 'Janeiro',
        2  => 'Fevereiro',
        3  => 'Março',
        4  => 'Abril',
        5  => 'Maio',
        6  => 'Junho',
        7  => 'Julho',
        8  => 'Agosto',
        9  => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];

    if ($mes == '') {
        return;
    }

    $mes_atual = $meses[$mes];

    return $mes_atual;
}


function verificar_sessao()
{
    //Verifica se existe algum dado de usuario da sessão, se n tiver devolve pra tela de login.php
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    //Configura um tempo de inatividade para desconectar!
    time_out();
}



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
    $conn->close();

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

//Função de coleta de todas datas do usuario para consulta
function horas_registradas($id_user)
{

    $conn = conexao_banco();

    $stmt = $conn->prepare(
        'SELECT * FROM ponto_diario WHERE usuario_id = ?'
    );
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializa o array
    $datas_registradas = [];

    while ($row = $result->fetch_assoc()) {
        // data_completo deve estar no formato YYYY-MM-DD
        $datas_registradas[$row['data_completo']] = $row;
    }

    return $datas_registradas;
}
