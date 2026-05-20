<?php

namespace App\helpers;

class PermissionHelper
{
    //Verificar Permissão de Administrador
    public static function isAdmin($user): bool
    {
        return $user->role === 'administrador';
    }

    //Verificar Tipos de Cargo

    public static function isCoordenador($user): bool
    {
        return $user->cargo === 'Coordenador';
    }

    public static function isPPE($user): bool
    {
        return $user->cargo === 'PPE';
    }

    public static function isServidorPublico($user): bool
    {
        return $user->cargo === 'Servidor Publico';
    }

    public static function isEstagiarioManha($user): bool
    {
        return $user->cargo === 'Estagiario-Manha';
    }

    public static function isEstagiarioTarde($user): bool
    {
        return $user->cargo === 'Estagiario-Tarde';
    }
}