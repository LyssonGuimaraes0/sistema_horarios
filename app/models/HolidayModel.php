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

    //Cria registro de feriado, caso true retorna id

    public function create(array $data, $idHoliday = false)
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

        if ($idHoliday === true) {
            return $pdo->lastInsertId();
        }

        return;

    }

    //Cria registro de Ponto Facultativo

    public function createOptionalHolidays(int $idHoliday, int $idRole, array $data, array $horarios)
    {
        $pdo = Database::connect();

        $sql = "INSERT INTO ponto_facultativo (";

        //Formata colunas e valores
        foreach ($horarios as $horario => $valor) {
            if ($horario == array_key_first($horarios)) {
                $sql .= "id_feriado, 
                        id_cargo, 
                        data_inicio, 
                        data_fim, ";

                $ValueSQL = ") VALUES( 
                        :id_feriado, 
                        :id_cargo, 
                        :data_inicio, 
                        :data_fim,";
            }

            if ($horario == array_key_last($horarios)) {
                $ValueSQL .= ":$horario)";
                $sql .= "$horario ";
            } else {
                $sql .= "$horario, ";
                $ValueSQL .= ":$horario, ";
            }
        }

        $sql = $sql . $ValueSQL;

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':id_feriado', $idHoliday);
        $stmt->bindValue(':id_cargo', $idRole);
        $stmt->bindValue(':data_inicio', $data['dataStart']); // Corrigido de :dateStart para :data_inicio
        $stmt->bindValue(':data_fim', $data['dataEnd']);

        foreach ($horarios as $horario => $valor) {
            $stmt->bindValue(":$horario", $valor);
        }

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