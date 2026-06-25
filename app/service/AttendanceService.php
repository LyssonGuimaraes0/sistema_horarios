<?php

namespace App\service;

use App\helpers\DirectoryHelper;
use App\models\user\UserModal;
use App\models\AttendanceModel;
use App\service\DateService;
use App\service\HolidayService;
use App\models\attachmentModel;


class AttendanceService
{
    private $userModal;
    private $attendanceModel;
    private $dateService;
    private $holidayService;
    private $attachmentModel;
    public function __construct()
    {
        $this->userModal = new UserModal;
        $this->attendanceModel = new AttendanceModel;
        $this->attachmentModel = new attachmentModel;
        $this->dateService = new DateService;
        $this->holidayService = new HolidayService;
    }

    //Registra novo horario ao banco de dados
    public function createAttendance($id, $dados)
    {

        //Cria Verificação de Horarios existente Conflict 409

        //Verifica se os array de horarios esta vazio
        foreach ($dados['attendance'] as $horario) {
            if (!isset($horario) || $horario == "") {
                throw new \Exception("Horarios em falta");
            }
        }


        return $this->attendanceModel->create($id, $dados['date'], $dados['status'], $dados['attendance']);
    }

    //Atualiza registro de horario

    public function updateAttendance($id, $dados)
    {

        //Verifica se os array de horarios esta vazio
        foreach ($dados['attendance'] as $horario) {
            if (!isset($horario) || $horario == "") {
                throw new \Exception("Horarios em falta");
            }
        }


        return $this->attendanceModel->updateAttendance($id, $dados['date'], $dados['attendance']);
    }


    //Deleta horario
    public function deleteAttendance($id, $date)
    {
        return $this->attendanceModel->delete($id, $date);
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
            $this->dateService->getCurrentMonth() : 12;

        $formatTimesSheets = [];

        for ($month = $AllMonths; $month >= 1; $month--) {
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

    public function uploadAttachment(int $id, $dados)
    {

        try {

            //Valida dados
            if (!isset($dados['dateStart']) || !isset($dados['dateEnd']) || !isset($dados['descricao_motivo']) || !isset($_FILES['file'])) {
                throw new \Exception("Dados incompletos - " . $dados['descricao_motivo']);
            } else {

                //caminho 
                $caminho = STORAGE_PATH . "/atestados/";

                //nome arquivo
                $nomeArquivo = uniqid() . "_" . basename($_FILES['file']['name']);

                // patch
                $path = $caminho . $nomeArquivo;

                //Verificar caso diretorio existe
                DirectoryHelper::verifyDirectory($caminho);

                //Mover arquivo para pasta de uploads
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                    throw new \Exception("Erro ao mover arquivo");
                }

                //Remove base padrão
                $caminhoFormatado = str_replace(BASE_PATH, "", $path);

                //Salvar dados no banco de dados
                $this->attachmentModel->create(
                    $id,
                    $dados['dateStart'],
                    $dados['dateEnd'],
                    $dados['descricao_motivo'],
                    $caminhoFormatado
                );

                return true;

            }

        } catch (\Exception $e) {
            throw new \Exception("Erro de validação: " . $e->getMessage(), 400);
        }

    }

}
