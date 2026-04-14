<?php

require_once __DIR__ . '/../app/controllers/UsuarioControllers.php';

//Verificar Permissão do usuario para decidir acesso
function verificar_permissoes()
{
     $DadosUsuario = dados_user();

    switch ($DadosUsuario['permissoes']) {
        case 'administrador':
            $navbar = 'navbar-admin.html';
            break;

        case 'usuario':
            $navbar = 'navbar.html';
            break;

    }

    return $navbar;
}