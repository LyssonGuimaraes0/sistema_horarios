<?php

namespace App\service;

use App\helpers\DirectoryHelper;
use App\models\user\UserModal;
use App\models\AttendanceModel;
use App\service\DateService;
use App\service\HolidayService;
use App\models\attachmentModel;
use App\models\CargoModel;
use App\models\MonthlyAttendanceModel;
use DateInterval;
use DatePeriod;
use DateTime;


class AttendanceService
{
    private $userModal;
    private $attendanceModel;
    private $dateService;
    private $holidayService;
    private $attachmentModel;
    private $monthlyAttendanceModel;


    public function __construct()
    {
        $this->userModal = new UserModal;
        $this->attendanceModel = new AttendanceModel;
        $this->attachmentModel = new attachmentModel;
        $this->dateService = new DateService;
        $this->holidayService = new HolidayService;
        $this->monthlyAttendanceModel = new MonthlyAttendanceModel;
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
    public function getAttendace($year, $month, $id)
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

        //Busca horarios de atestados do mes 
        $allCertificate = $this->attachmentModel->getAttachment($id, $year, $month);

        // Verifica se uma data está dentro de algum atestado
        function findCertificate(string $date, array $certificates): ?array
        {
            foreach ($certificates as $certificate) {
                if ($date >= $certificate['data_inicio'] && $date <= $certificate['data_fim']) {

                    //Caso seja do tipo deletado ignora
                    if ($certificate['deletado'] === 1) {
                        continue;
                    }

                    return [
                        "id" => $certificate['id'],
                        "data_inicio" => $certificate['data_inicio'],
                        "data_fim" => $certificate['data_fim'],
                        "path" => $certificate['caminho_justificativa']
                    ];
                }
            }
            return null;
        }

        //Organizar os dados coletados
        $AllAttendaceMonth = [];

        foreach ($allDateMonth as $day) {

            $foundAttendance = false;
            $certificate = findCertificate($day['date'], $allCertificate);

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
                        ],
                        "certificate" => $certificate
                    ];
                    $foundAttendance = true;
                    break;
                }
            }

            if (!$foundAttendance) {
                $AllAttendaceMonth[] = [
                    $day,
                    "feriado" => ($this->holidayService->isHoliday($day['date']) ? $holidayName[$day['date']] : false),
                    "attendance" => null,
                    "certificate" => $certificate
                ];
            }
        }

        return $AllAttendaceMonth;
    }

    //Obter folha mensal por ano
    public function getTimesheets(int $year, int $id)
    {

        //Busca Registros de folha de ponto
        $allTimesSheets = $this->monthlyAttendanceModel->getTimesheetsbyYear($id, $year);

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

    //Obter folha mensal por ano
    public function getMonthlyAttendance($year, $month, $id)
    {
        //Busca Registros de folha de ponto mensal
        return $this->monthlyAttendanceModel->getMonthlyAttendanceByYearAndMonth($id, $year, $month);
    }

    //Gera folha de ponto Mensal
    public function generateAttendancePdf(int $id, int $year, int $month)
    {

        $lastDay = DateService::getAllDaysOfMonth($year, $month);

        $listMonths = $this->dateService->getListNameMonth();

        $dateCurrent = $this->dateService->getDateComplete();

        //Formata data
        $dataStart = "$year-$month-01";
        $dataEnd = "$year-$month-$lastDay";

        //Prepara array de datas com horarios

        return [
            'nameMonth' => $listMonths[$month],
            'year' => $year,
            'dateCurrent' => date('d/m/Y', strtotime($dateCurrent)),
            'arrayDate' => $this->getAttendace($year, $month, $id),
            'user' => $this->userModal->findUserById($id),
            'dataStart' => date('d/m/Y', strtotime($dataStart)),
            'dataEnd' => date('d/m/Y', strtotime($dataEnd)),
        ];


    }


    //Upload de folha de ponto
    public function uploadMonthlyAttendance($id, $date, $file)
    {

        //Valida dados
        if (!isset($date['year']) || !isset($date['month']) || !isset($file)) {
            throw new \Exception("Dados enviados estão incompletos");
        }

        //Buscar dados do usuario
        $user = $this->userModal->findUserById($id);
        $name = preg_replace(
            '/[^a-z0-9_]/',
            '',
            str_replace(
                ' ',
                '_',
                strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $user['nome']))
            )
        );

        $setor = strtolower($user['setor']);

        //Verifica pasta de setor
        $caminho = STORAGE_PATH . "/folha_mensal/" . "$setor/";
        DirectoryHelper::verifyDirectory($caminho);

        //Formata pasta de user

        //Verifica pasta de usuario
        $caminho = $caminho . $name . "/";
        DirectoryHelper::verifyDirectory($caminho);

        $extensao = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        //nome arquivo
        $nomeArquivo = "folha_mensal_" . uniqid() . "_" . $date['year'] . "_" . $date['month'] . ".$extensao";

        // patch
        $path = $caminho . $nomeArquivo;

        //Verificar caso diretorio existe
        DirectoryHelper::verifyDirectory($caminho);

        //Verifica se já existe um documento existente
        if ($this->monthlyAttendanceModel->monthlyAttendanceExists($id, $date['year'], $date['month'])) {
            throw new \Exception("Já existe folha de ponto para este mês", 409);
        }

        //Mover arquivo para pasta de uploads
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            throw new \Exception("Erro ao mover arquivo", 500);
        }

        //Remove base padrão
        $newpath = str_replace(BASE_PATH, "", $path);

        //Armazena dados no banco
        if ($this->monthlyAttendanceModel->create($id, $date['year'], $date['month'], $newpath)) {
            throw new \Exception("Erro ao tenta cadastrar arquivo", 500);
        }

        return;

    }


    //Upload de atestados
    public function uploadAttachment(int $id, $dados, $role)
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


                //Coleta horario padrão do cargo
                $roleSchedule = CargoModel::getDefaultScheduleByRole($role);
                //Limpa dados que possão vim nulos
                $roleSchedule = array_filter($roleSchedule, fn($valor) => $valor !== null);

                //Realiza registros em todas datas correspondentes ao atestado
                $dataInicio = new DateTime($dados['dateStart']);
                $dataFim = new DateTime($dados['dateEnd']);

                $dataFim->modify('+1 day');

                //Cria periodo de datas
                $periodo = new DatePeriod(
                    $dataInicio,
                    new DateInterval('P1D'),
                    $dataFim
                );

                foreach ($periodo as $data) {
                    //Cria registro na folha de ponto
                    $this->attendanceModel->create($id, $data->format('Y-m-d'), "Atestado", $roleSchedule);
                }

                return true;

            }

        } catch (\Exception $e) {
            throw new \Exception("Erro de validação: " . $e->getMessage(), 400);
        }

    }

    //Deleta atestado
    public function deleteAttachment(int $idUser, int $idCertificate)
    {

        //Valida dados
        if (!isset($idCertificate)) {
            throw new \Exception("Registro do atestado em falta");
        }

        //Buscar dados do Atestado
        $certicate = $this->attachmentModel->getAttachmentById($idCertificate, $idUser);

        // Caso não possua registro do atestado ou já tenha sido deletado
        if ($certicate === false || $certicate['deletado'] === 1) {
            throw new \Exception("Atestado não foi encontrado", 404);
        }

        //nome arquivo
        $nomeArquivo = basename(urldecode($certicate["caminho_justificativa"]));

        // caminho lixeira 
        $caminhoLixeira = STORAGE_PATH . "/lixeira/";
        $caminhoAtual = BASE_PATH . $certicate["caminho_justificativa"];

        DirectoryHelper::verifyDirectory($caminhoLixeira);

        $pathLixeira = $caminhoLixeira . $nomeArquivo;
        $pathAtual = $caminhoAtual;

        //Formata nome do caminho do arquivo
        $pathFormatado = str_replace(BASE_PATH, "", $pathLixeira);

        if (!rename($pathAtual, $pathLixeira)) {
            throw new \Exception("Erro ao processar arquivo no servidor.");
        }

        //Salvar dados no banco de dados
        $this->attachmentModel->delete(
            $idUser,
            $idCertificate,
            $pathFormatado
        );

        //Remove registros de folha de ponto diaria
        $start = new DateTime($certicate['data_inicio']);
        $end = new DateTime($certicate['data_fim']);
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $date) {
            $onlyDate = $date->format('Y-m-d');
            $this->attendanceModel->delete($idUser, $onlyDate);
        }


    }
}
