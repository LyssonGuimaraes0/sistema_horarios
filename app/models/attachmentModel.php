<?php

namespace App\models;

use App\database\Database;
use Exception;
use PDO;

class AttachmentModel
{
    public function create(int $usuario_id, string $data_inicio, string $data_fim, string $descricao_motivo, string $caminho_justificativa){
        try{
            $pdo = Database::connect();

            $sql = "INSERT INTO documento_justificativa (usuario_id, data_inicio, data_fim, descricao_motivo, caminho_justificativa) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario_id, $data_inicio, $data_fim, $descricao_motivo, $caminho_justificativa]);
        }
        catch(\PDOException $e){
            throw new \Exception("Erro ao salvar no banco de dados: " . $e->getMessage(), 500);
        }
    }

    public function delete(int $usuario_id, int $documento_id, string $caminho_justificativa){
        try{
            $pdo = Database::connect();

            $sql = "UPDATE documento_justificativa set usuario_id = ? , caminho_justificativa = ?, deletado = 1 WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario_id, $caminho_justificativa, $documento_id]);
        }
        catch(\PDOException $e){
            throw new \Exception("Erro ao salvar no banco de dados: " . $e->getMessage(), 500);
        }

    }
}