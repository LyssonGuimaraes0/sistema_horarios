<?php

namespace App\controller\api;
use App\middleware\AuthMiddleware;
use App\service\AttendanceService;

class AttendanceController
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

        $this->attendanceService->createAttendance($id = 1,$dadosJson);
                
    }

}






?>