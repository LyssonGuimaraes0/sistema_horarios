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
        "01" => 'Janeiro',
        "02" => 'Fevereiro',
        "03" => 'Março',
        "04" => 'Abril',
        "05" => 'Maio',
        "06" => 'Junho',
        "07" => 'Julho',
        "08" => 'Agosto',
        "09" => 'Setembro',
        "10" => 'Outubro',
        "11" => 'Novembro',
        "12" => 'Dezembro'
    ];

    $mes_nome = $meses[$mes];

    $ano_limite = 2026;

    // Retorna como array associativo
    return [
        'dia' => $dia,
        'mes_nome' => $mes_nome,
        'mes' => $mes,
        'ano' => $ano,
        'data_completa' => "$dia/$mes/$ano",
        'meses' => $meses,
        'ano_limite' => $ano_limite

    ];
}

//Coleta datas de feriados

function feriados($ano)
{
    $conn = conexao_banco();

    $ano_selecionado = $ano;

    $query = $conn->prepare("SELECT ano FROM feriados WHERE ano = ? LIMIT 1");

    $query->bind_param("s", $ano_selecionado);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    //Se ja possuir registro do ano, não adicionar feriados
    if (!$row) {

        //Busca na API os feriados do Ano
        $token = "25310|FDLu0dz4YNyrUvPyFPs9ZK9sP4zp2LlB";

        $url = "https://api.invertexto.com/v1/holidays/$ano_selecionado?token=$token";

        //Prepara conexão HTTP

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resposta = curl_exec($ch);

        //fecha conexão
        curl_close($ch);

        //Converte resposta de JSON para array
        $feriados = json_decode($resposta, true);

        //Faz um loop para registrar todos os feriados do ano
        foreach ($feriados as $f) {
            $data_array = explode("-", $f['date']);

            $dia_mes_feriado = $data_array[2] . "/" . $data_array[1];
            $nome_feriado = $f['name'];

            //Prepara query para realizar registro no banco         
            $query = $conn->prepare("INSERT INTO feriados (feriado,dia_mes,ano) VALUES (?,?,?)");

            //Armazena no banco
            $query->bind_param("sss", $nome_feriado, $dia_mes_feriado, $ano_selecionado);
            $query->execute();

        }

    }

    //Coleta todos dados do formulario

    $query = $conn->prepare("SELECT feriado,dia_mes,ano FROM feriados WHERE ano = ?");
    $query->bind_param("s", $ano_selecionado);
    $query->execute();
    $result = $query->get_result();

    //Array para armazena datas e nome dos feriados
    $lista_feriados = [];

    while ($row = $result->fetch_assoc()) {
       $data_formatada = $row['dia_mes'] . '/' . $row['ano'];
       $lista_feriados[$data_formatada] = $row['feriado'];
    }
    $query->close();

    return $lista_feriados;

}



function verificar_sessao()
{
    //Verifica se existe algum dado de usuario da sessão, se n tiver devolve pra tela de login.php
    if (!isset($_SESSION['user_id'])) {
        if (defined('AJAX')) {
            http_response_code(401);
            echo json_encode(['status' => 'unauthorized']);
            exit;
        }

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

//Função de erro em login onde passa para o
function error_login()
{
    $error_login = $_SESSION['error_login'] ?? false;
    unset($_SESSION['error_login']);
}

//Coleta dados do usuario

function dados_user()
{

    $conn = conexao_banco();

    $usuario_id = $_SESSION['user_id'];

    $query = $conn->prepare("SELECT u.id, 
    u.nome,
    u.cpf,
    u.setor,
    u.cargo,
    u.permissoes,
    u.username,
    u.email
    FROM usuario u WHERE u.id = ?;");

    $query->bind_param("s", $usuario_id);

    $query->execute();
    $resultado = $query->get_result();
    $dados_usuario = $resultado->fetch_assoc();
    $conn->close();

    return $dados_usuario;
}

//Coleta horarios de cargo

function horario_cargo()
{

    $conn = conexao_banco();

    $dados_usuario = dados_user();

    $query = $conn->prepare("SELECT c.cargo, 
    c.cargo_entrada,
    c.cargo_saida_almoco,
    c.cargo_volta_almoco,
    c.cargo_saida
    FROM cargo c WHERE c.id = ?;");

    $query->bind_param("i", $dados_usuario['cargo']);

    $query->execute();
    $resultado = $query->get_result();
    $horarios_cargo = $resultado->fetch_assoc();

    $conn->close();

    return $horarios_cargo;

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

    $ponto_result = [];

    if ($resultado) {
        while ($row = $resultado->fetch_assoc()) {
            $ponto_result[$row['mes']] = $row;
        }
    } else {
        $ponto_result === null;
    }
    $conn->close();

    return $ponto_result;
}

//Função para coletar dados sobre setores registrados no banco

function setores()
{
    $conn = conexao_banco();

    $query = $conn->prepare('SELECT DISTINCT setor FROM usuario');
    $query->execute();
    $result = $query->get_result();
    $setores = [];

    while ($row = $result->fetch_assoc()) {
        $setores[] = $row['setor'];
    }

    $conn->close();

    return $setores;
}

//Função para coletar dados sobre usuarios de cada setor registrados no banco

function coletar_user()
{
    $conn = conexao_banco();
    //Organizar os nomes em ordem
    $query = $conn->prepare('SELECT * FROM usuario ORDER BY nome ASC');
    $query->execute();
    $result = $query->get_result();
    $usuarios_coletados = [];

    while ($row = $result->fetch_assoc()) {
        $usuarios_coletados[] = $row;
    }

    $conn->close();

    return $usuarios_coletados;
}

//Limpar variaveis de sessão caso outra pagina seja acessada

function limparFiltros()
{
    if (basename($_SERVER['PHP_SELF']) !== 'buscar_usuario.php') {
        unset(
            $_SESSION['usuario_selecionado'],
            $_SESSION['setor_selecionado'],
            $_SESSION['ano_selecionado']
        );
    }
}
