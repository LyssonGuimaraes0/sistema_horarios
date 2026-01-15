<?php
//Configurações de Datas do Servidor :(
date_default_timezone_set('America/Sao_Paulo');


//verifica mes correspondente


function mese_atual()
{

    // Pega dia, mês e ano
    $dia = date("d");   // 01 a 31
    $mes = date("m");   // 01 a 12
    $ano = date("Y");   // Ex: 2025

    //Nome do mes correspondente

    $meses = [
        "01"  => 'Janeiro',
        "02"  => 'Fevereiro',
        "03"  => 'Março',
        "04"  => 'Abril',
        "05"  => 'Maio',
        "06"  => 'Junho',
        "07"  => 'Julho',
        "08" => 'Agosto',
        "09" => 'Setembro',
        "10" => 'Outubro',
        "11" => 'Novembro',
        "12" => 'Dezembro'
    ];

    $mes_nome = $meses[$mes];

    // Retorna como array associativo
    return [
        'dia' => $dia,
        'mes_nome' => $mes_nome,
        'mes' => $mes,
        'ano' => $ano,
        'data_completa' => "$dia/$mes/$ano",
        'meses'      => $meses

    ];
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

    $conn->close();

    return $datas_registradas;
}

function folha_ponto_registro($id_user, $mes, $ano)
{
    $conn = conexao_banco();

    $query = $conn->prepare('SELECT * FROM folha_ponto_mensal WHERE usuario_id = ? AND mes = ? AND ano = ?');
    $query->bind_param('iss', $id_user, $mes, $ano);
    $query->execute();
    $resultado = $query->get_result();
    $ponto_result = $resultado->fetch_assoc();
    $conn->close();

    return $ponto_result;
}
