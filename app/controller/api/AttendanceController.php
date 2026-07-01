<?php

namespace App\controller\api;

use App\middleware\AuthMiddleware;
use App\service\AttendanceService;
use App\helpers\PermissionHelper;
use App\service\PdfService;
use Exception;


class AttendanceController extends ApiController
{

    private $authMiddleware;
    private $attendanceService;
    private $pdfService;

    public function __construct()
    {
        $this->authMiddleware = new AuthMiddleware;
        $this->attendanceService = new AttendanceService;
        $this->pdfService = new PdfService;
    }

    public function create()
    {
        try {
            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $dados = json_decode(file_get_contents('php://input'), true);

            //Enviar Horarios do usuario
            $this->attendanceService->createAttendance($user->id, $dados);

            return $this->success("Registro Realizado com sucesso!", 201);
        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 400);
        }
    }

    //Deleta registro de registro de horario
    public function deleteAttendance()
    {

        try {

            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $dados = json_decode(file_get_contents('php://input'), true);
            $date = $dados['date'];

            //Delete Horarios do usuario
            $this->attendanceService->deleteAttendance($user->id, $date);

            return $this->success("Registro deletado com sucesso!", 200);

        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 400);
        }
    }

    //Deleta registro de registro de horario
    public function updateAttendance()
    {

        try {
            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $dados = json_decode(file_get_contents('php://input'), true);

            //Delete Horarios do usuario
            $this->attendanceService->updateAttendance($user->id, $dados);

            return $this->success("Registro alterado com sucesso!", 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }


    }

    //Coleta mês validos baseado no mes atual
    public function availablePeriods()
    {
        $availableDate = $this->attendanceService->getAvailableMonths();

        return $this->success($availableDate);
    }

    public function getCalendar(int $year, int $month)
    {
        //Buscar dados de usuario
        $user = $this->authMiddleware->handle(); 

        $allDate = $this->attendanceService->getAttendace($year, $month, $user->id);

        $monthlyAttendance = $this->attendanceService->getMonthlyAttendance($year, $month, $user->id);

        $monthlyRecord = [
            "monthlyAttendance" => ($monthlyAttendance != false) ? true : false,
            "record" => $allDate
        ];

        return $this->success($monthlyRecord);
    }




    //Busca folha de Ponto Mesal por Ano
    public function getUserTimesheet(int $id)
    {

        //Coleta id do usuario Logado
        $user = $this->authMiddleware->handle();

        //Verificar se tem permissão de admin
        if (!PermissionHelper::isAdmin($user)) {
            throw new Exception("Usuario não tem permissão", 401);
        }

        $year = (int) filter_input(INPUT_GET, 'year', FILTER_SANITIZE_SPECIAL_CHARS);

        try {

            $allTimesSheets = $this->attendanceService->getTimesheets($year, $id);

            return $this->success($allTimesSheets, 200);
        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 500);
        }
    }

    // Registro de atestado
    public function createAttachment()
    {

        try {
            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $dados = json_decode(file_get_contents('php://input'), true) != null ? json_decode(file_get_contents('php://input'), true) : $_POST;

            // upload anexo
            $return = $this->attendanceService->uploadAttachment($user->id, $dados);

            if ($return === true) {
                return $this->success("Registro Realizado com sucesso!", 201);
            } else {
                return $this->error("Erro ao realizar upload", 400);
            }

        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 400);
        }

    }

    // Criação de PDF de folha de ponto
    public function attendancePdf()
    {

        try {
            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $year = filter_var($_GET['year'], FILTER_VALIDATE_INT) ?: null;
            $month = filter_var($_GET['month'], FILTER_VALIDATE_INT) ?: null;

            //validação de dados
            if (!isset($year) || !isset($month)) {
                throw new Exception("Parametros de requisição invalido");
            }

            $dados = $this->attendanceService->generateAttendancePdf($user->id, $year, $month);

            //Remove de array e transforma chave em variavel
            extract($dados);

            $this->pdfService->render(
                'components/attendance-pdf.php',
                $dados
            );

        } catch (Exception $e) {

            return $this->error($e->getMessage(), 500);
        }

    }

    // Armazena folha de ponto mensal
    public function storeMonthlyAttendance()
    {

        try {
            //Valida Token de acesso
            $user = $this->authMiddleware->handle();

            $date = json_decode(file_get_contents('php://input'), true) != null ? json_decode(file_get_contents('php://input'), true) : $_POST;
            $file = $_FILES['file'];

            $this->attendanceService->uploadMonthlyAttendance($user->id, $date, $file);

            return $this->success("Registro Realizado com sucesso!", 201);

        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 409) {
                return $this->error($e->getMessage(), 409);
            }

            if ($statusCode === 500) {
                return $this->error($e->getMessage(), 500);
            }

            return $this->error($e->getMessage(), 401);
        }

    }



    public function deleteAttachment()
    {

        try {
            //Valida Token de acesso
            //$user = $this->authMiddleware->handle();

            $dados = json_decode(file_get_contents('php://input'), true) != null ? json_decode(file_get_contents('php://input'), true) : $_POST;

            // upload anexo
            $return = $this->attendanceService->deleteAttachment($user->id ?? 3, $dados);

            if ($return === true) {
                return $this->success("Registro apagado com sucesso!", 201);
            } else {
                return $this->error("Erro ao apagar o arquivo", 400);
            }

        } catch (Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 400);
        }

    }

}
