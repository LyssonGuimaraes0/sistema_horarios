<?php

namespace App\controller\api;

use App\middleware\AuthMiddleware;
use App\service\AttendanceService;
use App\helpers\PermissionHelper;
use Exception;


class AttendanceController extends ApiController
{

    private $authMiddleware;
    private $attendanceService;


    public function __construct()
    {
        $this->authMiddleware = new AuthMiddleware;
        $this->attendanceService = new AttendanceService;
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
            $this->attendanceService->updateAttendance( $user->id, $dados);

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

        return $this->success($allDate);
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
}
