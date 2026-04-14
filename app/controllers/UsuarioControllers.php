<?php 
require_once __DIR__ . '/../../models/UsuarioModel.php';

function CarregarDadosUsuario(){

    $DadosUsuario = dados_user();

    return $DadosUsuario;
}







?>