<?php

namespace App\models\user;

use App\database\Database;

use PDO;

class AuthUserModal
{

    //Busca User pelo Username
    public function FindUserByUsername($username)
    {
        $pdo = Database::connect();

        $sql = "SELECT u.id,
        u.username,
        u.email,
        u.senha,
        u.permissoes,
        c.cargo 
        FROM usuario AS u
        INNER JOIN cargo AS c ON u.cargo = c.id
        WHERE u.username = :username 
        LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    //Busca User pelo Email
    public function FindUserByEmail($email)
    {
        $pdo = Database::connect();

        $sql = "SELECT id,email,senha FROM usuario WHERE email = :email LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }


}


?>