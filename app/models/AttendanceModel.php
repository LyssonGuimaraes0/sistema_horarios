<?php

namespace App\models;
use App\database\Database;
use PDO;

class AttendanceModel
{

    //Adição de novo horario ao banco de dados
    public function create($id, $data, $horarios)
    {
        $pdo = Database::connect();

        $sql = "INSERT INTO ponto_diario (
        usuario_id, data_completo, ";

        foreach ($horarios as $horario =>$valor){
            if ($horario == array_key_last($horarios)) {
                $sql .= "$horario VALUE";
            }else{
                $sql .= "$horario, ";
            }

            
        }

        echo $sql;

        return;







        /* $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); */

    }

}


?>