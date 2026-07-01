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

    //Buscar Folha de ponto Mensal por ano
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


    //Busca folha de ponto mensal por mes e ano
    public function getMonthlyAttendanceByYearAndMonth(int $userId, int $year, int $month)
    {
        $pdo = Database::connect();

        $sql = "SELECT 
        caminho_folha_de_ponto,
        observacao_fechamento
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

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
