<?php

namespace App\models;
use App\database\Database;
use PDO;

class MonthlyAttendanceModel
{

    //Criar registro de folha de ponto mensal
public function create(int $userId, int $year, int $month, string $path, string $status = "analise"): void
{
    $pdo = Database::connect();

    $sql = "INSERT INTO folha_ponto_mensal (
                usuario_id,
                mes,
                ano,
                caminho_folha_de_ponto,
                observacao_fechamento
            ) VALUES (
                :user_id,
                :month,
                :year,
                :path,
                :status
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);
    $stmt->bindValue(':path', $path, PDO::PARAM_STR);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);

    $stmt->execute();
}

    //Verifica se existe registro daquele mes e ano
    public function monthlyAttendanceExists(int $userId, int $year, int $month): bool
    {
        $pdo = Database::connect();

        $sql = "SELECT 1 
        FROM folha_ponto_mensal
        WHERE usuario_id = :user_id
        AND ano = :year
        AND mes = :month
        LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':year', $year, PDO::PARAM_INT);
        $stmt->bindValue(':month', $month, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }
}
