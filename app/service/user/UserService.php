<?php

namespace App\service\user;

use App\models\user\UserModal;
use Exception;

class UserService
{

    private $userModal;

    public function __construct()
    {
        $this->userModal = new UserModal;
    }

    public function getDashboardUser($id)
    {
        //Dados Usuario
        $modalUser = $this->userModal->findUserById($id);

        if (!isset($modalUser)) {
            return null;
        }

        return [
            'nome' => $modalUser['nome']
        ];
    }

    //Obter dados de usuario pelo id

    public function getUserbyID(int $id)
    {
            //Dados Usuario
            $modalUser = $this->userModal->findUserById($id);

            if ($modalUser === false) {
                throw new Exception("Usuario não foi encontrado", 404);
            }

            return $modalUser;
    }

    //Buscar setores de usuarios cadastrados

    public function getSectors()
    {
        return $this->userModal->getSectors();
    }

    //Coleta nome de usuarios do setor
    public function getUsersBySector(string $setor)
    {
        $dados = $this->userModal->getUsersBySector($setor);

        $result = [];
        foreach ($dados as $dado) {
            $result[] = [
                "id" => $dado['id'],
                "name" => $dado['nome'],
                "role" => $dado['cargo']
            ];
        }
        return $result;
    }


    public function createUser($dados)
    {

        //Verifica se CPF já esta registrado
        $cpfModel = $this->userModal->checkCpfExists($dados['cpf']);

        if ($cpfModel === true) {
            throw new Exception("Usuario já esta registrado", 400);
        }

        //Cria hash da senha do usuario
        $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $dados['senha'] = $hash;

        //Definir permissão do usuario
        $dados['permissoes'] = ($dados['permissoes'] === true) ? "administrador" : "usuario";

        //Creação de dados de usuario
        $modalUser = $this->userModal->createUsuario($dados);

        if ($modalUser !== true) {
            throw new Exception("Usuario não pode ser cadastrado");

        }

        return true;
    }
}
