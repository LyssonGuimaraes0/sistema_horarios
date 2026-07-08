<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;
use App\helpers\PermissionHelper;
use App\service\HolidayService;
use App\service\AttendanceService;
use App\service\user\UserService;


class DashboardController
{

    private $webAuthMiddleware;
    private $holidayService;
    private $attendanceService;
    private $userService;

    public function __construct()
    {
        $this->webAuthMiddleware = new WebAuthMiddleware;
        $this->holidayService = new HolidayService;
        $this->attendanceService = new AttendanceService;
        $this->userService = new UserService;
    }


    public function index()
    {
        //Passa pela verificação de COOKIES
        $user = $this->webAuthMiddleware->handle();

        //Faz sycronização de feriados Atual
        $this->holidayService->syncYear(date('Y'));

        //dados user
        $dataUser = $this->userService->getUserbyID($user->id);

        //Buscar dados de horario do usuario
        $record = $this->attendanceService->getDataForDashboard($user->id);

        require_once VIEW_PATH . "/dashboard.php";
    }

}


?>