<?php

namespace App\models;

use App\database\Database;
use PDO;

class AttendanceModel
{

    //Adição de novo horario ao banco de dados
    public function create($id, $data, $status, $horarios)
    {
        try {
            $pdo = Database::connect();

            $pdo->beginTransaction();

            $sql = "INSERT INTO ponto_diario (
        usuario_id, data_completo, status_dia, ";

            //Formata colunas e valores
            foreach ($horarios as $horario => $valor) {
                if ($horario == array_key_last($horarios)) {
                    $sql .= "$horario) VALUE (";

                    //Monta tabela sql completo baseado nos dados existentes
                    foreach ($horarios as $horario => $valor) {
                        if ($horario == array_key_first($horarios)) {
                            $sql .= ":usuario_id, :data_completo, :status_dia,";
                        }

                        if ($horario == array_key_last($horarios)) {
                            $sql .= ":$horario)";
                        } else {

                            $sql .= ":$horario, ";
                        }
                    }
                } else {
                    $sql .= "$horario, ";
                }
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':data_completo', $data, PDO::PARAM_STR);
            $stmt->bindValue(':status_dia', $status, PDO::PARAM_STR);

            //Prepara dados de insert baseados nos arrays e
            foreach ($horarios as $horario => $valor) {
                $stmt->bindValue(":$horario", $valor, PDO::PARAM_STR);
            }

            $stmt->execute();

            $pdo->commit();

            return;
        } catch (\PDOException $e) {
            //Caso de de erro limpa registro
            $pdo->rollBack();
            return "Erro na execução: " . $e->getMessage();
        }
    }

    //Atualizar horario 
    public function updateAttendance($id, $data, $horarios)
    {
        try {
            $pdo = Database::connect();

            $pdo->beginTransaction();

            $sql = "UPDATE ponto_diario SET ";

            //Formata colunas e valores
            foreach ($horarios as $horario => $valor) {
                if ($horario == array_key_last($horarios)) {
                    $sql .= "$horario = :$horario ";

                } else {
                    $sql .= "$horario = :$horario, ";
                }
            }

            $sql .= "WHERE usuario_id = :usuario_id
                    AND data_completo = :data_completo";


            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':data_completo', $data, PDO::PARAM_STR);

            //Prepara dados de insert baseados nos arrays e
            foreach ($horarios as $horario => $valor) {
                $stmt->bindValue(":$horario", $valor, PDO::PARAM_STR);
            }

            $stmt->execute();

            $pdo->commit();

            return;
        } catch (\PDOException $e) {
            //Caso de de erro limpa registro
            $pdo->rollBack();
            return "Erro na execução: " . $e->getMessage();
        }
    }

    //Deletar registro de horario
    public function delete(int $id, string $date)
    {
        try {

            $pdo = Database::connect();

            $pdo->beginTransaction();

            $sql = "DELETE FROM ponto_diario
                WHERE usuario_id = :usuario_id
                AND data_completo = :data_completo";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':usuario_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':data_completo', $date, PDO::PARAM_STR);

            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new \Exception('Registro não encontrado');
            }

            $pdo->commit();

            return;
        } catch (\PDOException $e) {
            //Caso de de erro limpa registro
            $pdo->rollBack();
            return "Erro na execução: " . $e->getMessage();
        }
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
        saida,
        status_dia
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
