<?php

namespace App\models\user;

use App\database\Database;
use Exception;
use PDO;

class UserModal
{
    //Buscar dados Usuario

    public function findUserById($id)
    {
        $pdo = Database::connect();

        $sql = "SELECT u.id,
        u.nome,
        u.username,
        u.email,
        u.cpf,
        u.setor,
        u.permissoes,
        c.cargo
        FROM usuario AS u
        INNER JOIN cargo AS c ON u.cargo = c.id
        WHERE u.id = :id 
        LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUsuario($dados)
    {
        try {
            $pdo = Database::connect();

            $sql = "INSERT INTO usuario (
            nome, cpf, setor, permissoes, username, email, senha, cargo
            ) VALUES (
            :nome, :cpf, :setor, :permissoes, :username, :email, :senha, :cargo
            )";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome'], PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $dados['cpf'], PDO::PARAM_STR);
            $stmt->bindValue(':setor', $dados['setor'], PDO::PARAM_STR);
            $stmt->bindValue(':permissoes', $dados['permissoes'], PDO::PARAM_STR);
            $stmt->bindValue(':username', $dados['username'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $dados['email'], PDO::PARAM_STR);
            $stmt->bindValue(':senha', $dados['senha'], PDO::PARAM_STR);
            $stmt->bindValue(':cargo', $dados['cargo'], PDO::PARAM_INT);

            if (!$stmt->execute()) {
                throw new Exception('Erro ao armazena ao banco de dados');
            }

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
