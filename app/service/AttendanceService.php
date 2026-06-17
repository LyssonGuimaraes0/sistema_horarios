<?php

namespace App\service;
use App\models\user\UserModal;
use App\models\AttendanceModel;
use App\service\DateService;
use App\service\HolidayService;


class AttendanceService
{
    private $userModal;
    private $attendanceModel;
    private $dateService;
    private $holidayService;

    public function __construct()
    {
        $this->userModal = new UserModal;
        $this->attendanceModel = new AttendanceModel;
        $this->dateService = new DateService;
        $this->holidayService = new HolidayService;

    }

    //Registra novo horario ao banco de dados
    public function createAttendance($id, $dados)
    {

      //Cria Verificação de Horarios existente Conflict 409


      return $this->attendanceModel->create($id,$dados['date'],$dados['status'],$dados['attendance']);
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
            'years' => array_reverse($avaliableYear),
            'months' => array_reverse($availableMonths)
        ];
    }

    //Obter registros de horarios por mes e ano
    public function getAttendace(int $year, int $month, int $id): array
    {
        //Busca todas datas do mes
        $allDateMonth = $this->dateService->getAllDateOfMonth($year, $month);

        //Coleta primeira e ultima posição do array
        $arrayStart = array_key_first($allDateMonth);
        $arrayEnd = array_key_last($allDateMonth);

        $holidayName = $this->holidayService->getListHolidays($year);

        //Busca horarios registrados pelo usuario
        $attendaceUser = $this->attendanceModel->getAttendance(
            $id,
            $allDateMonth[$arrayStart]['date'],
            $allDateMonth[$arrayEnd]['date']
        );


        //Organizar os dados coletados
        $AllAttendaceMonth = [];

        foreach ($allDateMonth as $day) {
            $foundAttendance = false;
            foreach ($attendaceUser as $attendance) {
                if ($day['date'] === $attendance['data_completo']) {
                    $AllAttendaceMonth[] = [
                        $day,
                        "feriado" => ($this->holidayService->isHoliday($day['date']) ? $holidayName[$day['date']] : false),
                        "attendance" => [
                            "entrada" => $attendance['entrada'],
                            "saida_almoco" => $attendance['saida_almoco'],
                            "volta_almoco" => $attendance['volta_almoco'],
                            "saida" => $attendance['saida'],
                        ]
                    ];
                    $foundAttendance = true;
                    break;
                }
            }

            if (!$foundAttendance) {
                $AllAttendaceMonth[] = [
                    $day,
                    "feriado" => ($this->holidayService->isHoliday($day['date']) ? $holidayName[$day['date']] : false),
                    "attendance" => null
                ];
            }

        }

        return $AllAttendaceMonth;

    }
    //Obter folha mensal por ano
    public function getTimesheets(int $year, int $id)
    {

        //Busca Registros de folha de ponto
        $allTimesSheets = $this->attendanceModel->getTimesheetsbyYear($id, $year);

        //array de nomes de meses
        $nameMonth = $this->dateService->getListNameMonth();
        $curretYear = $this->dateService->getCurrentYear();

        $AllMonths = ($curretYear === $year) ? 
        $this->dateService->getCurrentMonth() : 12 ;

        $formatTimesSheets = [];

        for ($month = $AllMonths ; $month >= 1; $month--) {
            if (isset($allTimesSheets[$month])) {
                $formatTimesSheets[] = [
                    'month' => $nameMonth[$month],
                    'file' => $allTimesSheets[$month]
                ];
            } else {
                $formatTimesSheets[] = [
                    'month' => $nameMonth[$month],
                    'file' => null
                ];
            }
        }

        return $formatTimesSheets;

    }

}


?>