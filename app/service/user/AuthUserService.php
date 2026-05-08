<?php

namespace App\service\user;

use App\models\user\AuthUserModal;
use Exception;

class AuthUserService
{
    public function AuthUser($username, $password)
    {
        try {
            $AuthUserModal = new AuthUserModal;
            $consultData = $AuthUserModal->consultAuthUser($username);

            if (!password_verify($password, $consultData['senha'])) {
                throw new Exception();
                
            }

        } catch (Exception) {
            return null;
        }


    }


}





?>