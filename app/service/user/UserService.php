<?php

namespace App\service\user;

use App\models\user\UserModal;
use Exception;

class UserService
{

    private $userModal;

    public function __construct()
    {
        $this->userModal = new UserModal;

    }

    public function getDashboardUser($id)
    {
        //Dados Usuario
        $modalUser = $this->userModal->findUserById($id);

        if (!isset($modalUser)) {
            return null;
        }

        return [
            'nome' => $modalUser['nome']
        ];
    }

}





?>