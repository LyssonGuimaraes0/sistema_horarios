<?php 

//verifica mes correspondente

function BuscarMeses()
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




?>