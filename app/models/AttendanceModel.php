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

        foreach ($horarios as $horario => $valor) {
            if ($horario == array_key_last($horarios)) {
                $sql .= "$horario VALUE";
            } else {
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

    //Buscar registros de horarios
    public function getAttendance($id, $dateStart, $dateEnd)
    {
        $pdo = Database::connect();

        $sql = "SELECT 
        data_completo,
        entrada,
        saida_almoco,
        volta_almoco,
        saida
        FROM ponto_diario 
        WHERE usuario_id = :usuario_id
            AND data_completo >= :dateStart
            AND data_completo < :dateEnd    
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':usuario_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':dateStart', $dateStart, PDO::PARAM_STR);
        $stmt->bindValue(':dateEnd', $dateEnd, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    //Buscar Folha de ponto Mensal
    public function getTimesheetsbyYear($id, $year)
    {
        $pdo = Database::connect();

        $sql = "SELECT 
        mes,
        caminho_folha_de_ponto
        FROM folha_ponto_mensal 
        WHERE usuario_id = :usuario_id
        AND ano = :ano";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':usuario_id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':ano', $year, PDO::PARAM_INT);

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return array_column($resultados, 'caminho_folha_de_ponto', 'mes');

    }

}


?>