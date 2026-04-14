<?php 

//Adicionar as Variavesi global
function MontarRespostaModal($codigo,$mensagem){

    $_SESSION["codigo"] = $codigo;
    $_SESSION['mensagem'] = $mensagem;
    
    return;
}


//Verifica Respostas das Páginas
function VerificarRespostaModal()
{
    //Limpa Filtros Anteriores
     limparFiltros(); 

    //Coleta informações das Querys
    $codigo = $_SESSION['codigo'] ?? null;
    $mensagem = $_SESSION['mensagem'] ?? null;
    unset($_SESSION['codigo']); // remove após usar
    unset($_SESSION['mensagem']);

    return [
    'codigo' => $codigo,
    'mensagem' => $mensagem
    ];
}



?>