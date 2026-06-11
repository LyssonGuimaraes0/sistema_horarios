<?php

namespace App\controller\api;
use App\middleware\AuthMiddleware;
use App\service\AttendanceService;
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
        //Valida Token de acesso
        /*$user = $this->authMiddleware->handle(); */

        //Enviar Horarios do usuario
        $dadosJson = file_get_contents('php://input');

        $this->attendanceService->createAttendance($id = 1, $dadosJson);

    }

    //Coleta mês validos baseado no mes atual
    public function availablePeriods(): array
    {
        $availableDate = $this->attendanceService->getAvailableMonths();

        return $this->success($availableDate);

    }

    public function getCalendar(int $year, int $month)
    {
        $allDate = $this->attendanceService->getAttendace($year, $month);

        return  $this->success($allDate);
    }


}






?>