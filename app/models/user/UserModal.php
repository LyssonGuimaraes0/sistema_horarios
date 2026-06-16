<?php

namespace App\models\user;

use App\database\Database;
use Exception;
use PDO;

class UserModal
{
    //Buscar dados Usuario pelo ID

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

    //Verifica se Usuario existe pelo CPF

    public function checkCpfExists($cpf): bool
    {
        $pdo = Database::connect();

        $sql = "SELECT COUNT(*) as total FROM usuario WHERE cpf = :cpf";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retorna true se o total for maior que 0, se não, retorna false
        return $resultado['total'] > 0;
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

            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception("Erro: Usuário, CPF ou Email já cadastrado no sistema.");
            }

            // Se for outro erro de banco (coluna errada, tabela inexistente)
            throw new Exception("Erro no banco de dados: " . $e->getMessage());
        }

    }

    //Seleciona setores

    public function getSectors()
    {
        $pdo = Database::connect();

        $sql = "SELECT DISTINCT
        setor
        FROM usuario 
        ORDER BY setor ASC";

        $stmt = $pdo->prepare($sql);

        $stmt->execute();

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_column($resultado, 'setor');
    }

    //Seleciona nome do usuarios de setor 
    public function getUsersBySector($setor)
    {
        try {
            $pdo = Database::connect();

            $sql = "SELECT
        id,
        nome
        FROM usuario
        WHERE setor = :setor 
        ORDER BY nome ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':setor', $setor, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new Exception("Erro no banco de dados: " . $e->getMessage());
        }

    }
}
