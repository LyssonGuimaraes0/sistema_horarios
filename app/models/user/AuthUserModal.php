<?php 

namespace App\models\user;

use App\database\Database;

use PDO;

class AuthUserModal 
{
    public function consultAuthUser($username){
        $pdo = Database::connect();

        $sql = "SELECT username,senha FROM usuario WHERE username = :username LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    
}


?>