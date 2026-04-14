<?php

require_once '../../models/DatasModel.php';
require_once '../../models/UsuarioModel.php';

//Busca no banco e as informações de hoarios
function CarregarHorasRegistradas($id_user)
{
    $datas_registradas = BuscarHorasRegistradas($id_user);

    return $datas_registradas;
}


//Busca de Elementos de Data

function CarregarDatasDoUsuario()
{
    $dados_user = CarregarDadosUsuario();
    $IdUsuario = $dados_user['id'];

    $datas_registradas = CarregarHorasRegistradas($IdUsuario);

    //Define variaveis que seram utilizadas
    $data_str = date('Y-m-d');

    $registros = $datas_registradas;
    $totalMes = 0;
    
    //Total de registros realizados no mes
    foreach ($registros as $data => $registro) {
        [$ano_data, $mes_data, $dia_data] = explode("-", $data);

        if ($mes_data === $mes_atual && $ano_data === $ano_atual) {
            $totalMes++;
        }
    }

    //

    return [
        'registroDia' => $datas_registradas[$data_str] ?? null,
        'existeRegistro' => isset($datas_registradas[$data_str]),
        'dia' => date('j'),
        'nomeDiaIngles' => date('l', strtotime($data_str)),
        'totalMes' => $totalMes
    ];

}

//Carregar Horarios restantes do usuario 









?>