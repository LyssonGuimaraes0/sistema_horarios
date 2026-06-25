<?php

namespace App\helpers;

class DirectoryHelper
{
    //Verificar se diretorio existe
    public static function verifyDirectory($dir)
    {
        //Cria caso repositorio não exista
        if (!is_dir($dir)) {
            mkdir($dir, 0750, true);
            return;
        }

        return;
    }
}