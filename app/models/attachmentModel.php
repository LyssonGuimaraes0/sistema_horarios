<?php

namespace App\models;

use App\database\Database;
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
}