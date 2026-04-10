<?php
session_start();
include('conf_bd.php');
include('conf_server.php');
$conn = conexao_banco();

// Coleta dados
$data = $_POST['data-feriado'];
$data_inicio_ponto_facultativo = $_POST['inicio-ponto-facultativo'] ?? [];
$data_fim_ponto_facultativo = $_POST['fim-ponto-facultativo'] ?? [];
$tipo_envio = $_POST['selecao-tipo-funcionario'] ?? [];
$nome_feriado = $_POST['nome-feriado'];


$inputs = [
    "servidor_publico_entrada" => $_POST['servidor-publico-entrada'] ?? [],
    "servidor_publico_saida_almoco" => $_POST['servidor-publico-saida-almoco'] ?? [],
    "servidor_publico_volta_almoco" => $_POST['servidor-publico-volta-almoco'] ?? [],
    "servidor_publico_saida" => $_POST['servidor-publico-saida'] ?? [],

    "estagiario_manha_entrada" => $_POST['estagiario-manha-entrada'] ?? [],
    "estagiario_manha_saida" => $_POST['estagiario-manha-saida'] ?? [],
    "estagiario_tarde_entrada" => $_POST['estagiario-tarde-entrada'] ?? [],
    "estagiario_tarde_saida" => $_POST['estagiario-tarde-saida'] ?? [],
];

$dados = [];

//Quantidades de elementos

$qnts_elementos = count($data_inicio_ponto_facultativo);

for ($i = 0; $i < $qnts_elementos; $i++) {


    $registro = [
        "data_inicio" => $data_inicio_ponto_facultativo[$i] ?? null,
        "data_fim" => $data_fim_ponto_facultativo[$i] ?? null,
        "tipo" => $tipo_envio[$i] ?? null
    ];

    // adiciona todos os inputs automaticamente
    foreach ($inputs as $chave => $array) {
        $registro[$chave] = $array[$i] ?? null;
    }

    //Limpa campos baseados no tipo
    if ($registro['tipo'] == 'Ambos') {
        $dados[$data][] = $registro;
        continue;
    }

    if ($registro['tipo'] == 'Estagiario') {
        unset(
            $registro['servidor_publico_entrada'],
            $registro['servidor_publico_saida_almoco'],
            $registro['servidor_publico_volta_almoco'],
            $registro['servidor_publico_saida']
        );
    }

    if ($registro['tipo'] == 'Servidor Público') {
        unset(
            $registro['estagiario_manha_entrada'],
            $registro['estagiario_manha_saida'],
            $registro['estagiario_tarde_entrada'],
            $registro['estagiario_tarde_saida']
        );
    }

    $dados[$data][] = $registro;

}

// Formata data para base de dados
$array_data = explode("-", $data);
$dia_mes = "$array_data[2]/$array_data[1]";
$ano = $array_data[0];

$nome_feriado_formatado = "Ponto Facultativo - $nome_feriado";

//Busca informações de cargos
$query_cargo = $conn->prepare("SELECT id,cargo FROM cargo");
$query_cargo->execute();
$resultado = $query_cargo->get_result();
$cargos = $resultado->fetch_all(MYSQLI_ASSOC);

$mapa_cargos = [];

foreach ($cargos as $cargo) {
    //Guarda id de cada cargo
    $mapa_cargos[$cargo['cargo']] = $cargo['id'];
}
;




//Criação de Query para armazenamento na base de dados
$query_feriado = $conn->prepare('INSERT INTO feriados(feriado, dia_mes, ano) VALUES (?,?,?)');

$query_feriado->bind_param('sss', $nome_feriado_formatado, $dia_mes, $ano);

if (!$query_feriado->execute()) {
    $query_feriado->close();
    $conn->close();

    $_SESSION['cadastro'] = "falha";
    $_SESSION['mensagem'] = "Ocorreu um erro ao tentar registrar os dados, <br> tente novamente mais tarde.";
    header("location: ../ponto_facultativo.php");
    exit;
}
//Coleta ID de feriados
$id_feriado = $query_feriado->insert_id;

//== Query para PPE e Servidores Publico ==
$query_servidor_publico = $conn->prepare
('INSERT INTO ponto_facultativo(
id_feriado,
id_cargo,
data_inicio,
data_fim,  
horario_compensacao_entrada_manha,
horario_compensacao_saida_manha,
horario_compensacao_entrada_tarde,
horario_compensacao_saida_tarde
) VALUES (?,?,?,?,?,?,?,?)');

//== Query para Estagiarios  ==
$query_estagiario = $conn->prepare
('INSERT INTO ponto_facultativo(
id_feriado,
id_cargo,
data_inicio,
data_fim,  
horario_compensacao_entrada_manha,
horario_compensacao_saida_manha,
horario_compensacao_entrada_tarde,
horario_compensacao_saida_tarde
) VALUES (?,?,?,?,?,?,?,?)');


//loop para armazenar na tabela de ponto facultativo
for ($i = 0; $i < $qnts_elementos; $i++) {

    //verifica o tipo
    if ($dados[$data][$i]['tipo'] == "Servidor Público") {

        $cargos_selecionados = ['PPE', 'Servidor Público'];

        //Loop para altera id tanto PPE quanto Servidor
        foreach ($cargos_selecionados as $nome_cargo) {

            $id_cargo = $mapa_cargos[$nome_cargo];

            //Define query expecificas
            $query_servidor_publico->bind_param(
                'iissssss',
                $id_feriado,
                $id_cargo,
                $dados[$data][$i]['data_inicio'],
                $dados[$data][$i]['data_fim'],
                $dados[$data][$i]['servidor_publico_entrada'],
                $dados[$data][$i]['servidor_publico_volta_almoco'],
                $dados[$data][$i]['servidor_publico_saida_almoco'],
                $dados[$data][$i]['servidor_publico_saida']
            );

            if (!$query_servidor_publico->execute()) {
                $conn->close();

                $_SESSION['cadastro'] = "falha";
                $_SESSION['mensagem'] = "Ocorreu um erro ao tentar registrar os dados, <br> tente novamente mais tarde.";
                header("location: ../ponto_facultativo.php");
                exit;
            }
        }


    } elseif ($dados[$data][$i]['tipo'] == "Estagiario") {

        $cargos_selecionados = ['Estagiario-Manha', 'Estagiario-Tarde'];

        foreach ($cargos_selecionados as $nome_cargo) {
            $id_cargo = $mapa_cargos[$nome_cargo];

            $query_estagiario->bind_param(
                'iissssss',
                $id_feriado,
                $id_cargo,
                $dados[$data][$i]['data_inicio'],
                $dados[$data][$i]['data_fim'],
                $dados[$data][$i]['estagiario_manha_entrada'],
                $dados[$data][$i]['estagiario_manha_saida'],
                $dados[$data][$i]['estagiario_tarde_entrada'],
                $dados[$data][$i]['estagiario_tarde_saida']
            );


            if (!$query_estagiario->execute()) {
                $conn->close();

                $_SESSION['cadastro'] = "falha";
                $_SESSION['mensagem'] = "Ocorreu um erro ao tentar registrar os dados, <br> tente novamente mais tarde.";
                header("location: ../ponto_facultativo.php");
                exit;
            }
        }

    } elseif ($dados[$data][$i]['tipo'] == "Ambos") {

        $cargos_selecionados = ['PPE', 'Servidor Público', 'Estagiario-Manha', 'Estagiario-Tarde'];

        foreach ($cargos_selecionados as $nome_cargo) {

            $id_cargo = $mapa_cargos[$nome_cargo];

            //Verifica se é estagiaro se n joga em servidor
            if ($nome_cargo == "Estagiario-Manha" || $nome_cargo == "Estagiario-Tarde") {
                $query_estagiario->bind_param(
                    'iissssss',
                    $id_feriado,
                    $id_cargo,
                    $dados[$data][$i]['data_inicio'],
                    $dados[$data][$i]['data_fim'],
                    $dados[$data][$i]['estagiario_manha_entrada'],
                    $dados[$data][$i]['estagiario_manha_saida'],
                    $dados[$data][$i]['estagiario_tarde_entrada'],
                    $dados[$data][$i]['estagiario_tarde_saida']
                );

                $query_estagiario->execute();

            } else {
                $query_servidor_publico->bind_param(
                    'iissssss',
                    $id_feriado,
                    $id_cargo,
                    $dados[$data][$i]['data_inicio'],
                    $dados[$data][$i]['data_fim'],
                    $dados[$data][$i]['servidor_publico_entrada'],
                    $dados[$data][$i]['servidor_publico_volta_almoco'],
                    $dados[$data][$i]['servidor_publico_saida_almoco'],
                    $dados[$data][$i]['servidor_publico_saida']
                );
                $query_servidor_publico->execute();
            }

        }

    }

}
;

$query_servidor_publico->close();
$query_estagiario->close();
$conn->close();

//Reenvia para Página de inicio
$_SESSION['cadastro'] = "sucesso";
$_SESSION['mensagem'] = "Horarios foram registrados com sucesso!";
header("location: ../ponto_facultativo.php");









