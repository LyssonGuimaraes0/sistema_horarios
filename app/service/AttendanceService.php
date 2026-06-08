<?php

namespace App\service;
use App\models\user\UserModal;
use App\models\AttendanceModel;


class AttendanceService
{
    private $userModal;
    private $attendanceModel;

    public function __construct()
    {
        $this->userModal = new UserModal;
        $this->attendanceModel = new AttendanceModel;

    }

    //Adição de novo horario ao banco de dados
    public function createAttendance($id, $dadosJson)
    {
        $modalUser = $this->userModal->findUserById($id);
        if (!isset($modalUser)) {
            return null;
        }

        //Converte para array
        $dados = json_decode($dadosJson, true);

        // Acessando os valores diretamente:
        $data = $dados['registro']['data'];
        $horarios = $dados['registro']['horario'];

        //Enviar horarios do usuario
        $modalAttendance = $this->attendanceModel->create($id, $data, $horarios);



    }

}


?>