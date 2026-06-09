<?php

namespace App\models;
use App\database\Database;
use PDO;

class HolidayModel
{
    public function existsYear(int $year): bool
    {
        $pdo = Database::connect();

        $sql = "SELECT COUNT(*) AS total
        FROM feriados
        WHERE YEAR(data_completa) = :year
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $result['total'] > 0;
    }

    public function create(array $data): void
    {
        $pdo = Database::connect();

        $sql = "INSERT INTO feriados (
            feriado,
            data_completa
        ) VALUES (
            :feriado,
            :data_completa
        )";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':feriado', $data['name']);
        $stmt->bindValue(':data_completa', $data['date']);

        $stmt->execute();
    }
}



?>