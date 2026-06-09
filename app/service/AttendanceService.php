<?php

namespace App\service;
use App\models\user\UserModal;
use App\models\AttendanceModel;
use App\service\DateService;


class AttendanceService
{
    private $userModal;
    private $attendanceModel;
    private $dateService;

    public function __construct()
    {
        $this->userModal = new UserModal;
        $this->attendanceModel = new AttendanceModel;
        $this->dateService = new DateService;

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

    public function getAvailableMonths($limitYear = 2025): array
    {
        $currentMonth = $this->dateService->getCurrentMonth();
        $currentYear = $this->dateService->getCurrentYear();
        $listMonth = $this->dateService->getListNameMonth();

        //Cria lista de meses validos
        $avaliableYear = [];
        $availableMonths = [];

        //Definir lista de anos
        for ($i = $limitYear; $i <= $currentYear; $i++) {
            $avaliableYear[] = $i;
        }

        for ($i = 1; $i <= $currentMonth; $i++) {
            $availableMonths[] = [
                'id' => $i,
                'name' => $listMonth[$i]
            ];
        }

        return [
            'years' => $avaliableYear,
            'months' => $availableMonths
        ];

    }

}


?>