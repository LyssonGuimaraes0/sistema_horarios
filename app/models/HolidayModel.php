<?php

namespace App\models;
use App\database\Database;
use PDO;

class HolidayModel
{
    //Verificar se existe registro do ano Atual
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

    //Cria registro de feriado

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

    //Busca registros de feriados do ano

    public function getHolidays(int $year): array
    {
        $pdo = Database::connect();

        $sql = "SELECT feriado,
        data_completa 
        FROM feriados
        WHERE YEAR(data_completa) = :year";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Verifica se data é feriado
    public function existsDate(string $date): bool
    {
        $pdo = Database::connect();

        $sql = "
        SELECT EXISTS(
            SELECT 1
            FROM feriados
            WHERE data_completa = :date
        )
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

}



?>