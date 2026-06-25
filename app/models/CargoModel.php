<?php

namespace App\models;
use App\database\Database;
use PDO;

class CargoModel
{

    //Cria registro de feriado
    public static function getDefaultScheduleByRole($role)
    {
        $pdo = Database::connect();

        $sql = "SELECT
        cargo_entrada AS entrada,
        cargo_saida_almoco AS saida_almoco,
        cargo_volta_almoco AS volta_almoco,
        cargo_saida AS saida
        FROM cargo
        WHERE cargo = :cargo
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':cargo', $role);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

}



?>