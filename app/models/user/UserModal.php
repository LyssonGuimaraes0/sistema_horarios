<?php

namespace App\models\user;
use App\database\Database;

use PDO;

class UserModal
{
    //Buscar dados Usuario

    public function findUserById($id)
    {
        $pdo = Database::connect();

        $sql = "SELECT u.id,
        u.nome,
        u.username,
        u.email,
        u.cpf,
        u.setor,
        u.permissoes,
        c.cargo
        FROM usuario AS u
        INNER JOIN cargo AS c ON u.cargo = c.id
        WHERE u.id = :id 
        LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }


}


?>