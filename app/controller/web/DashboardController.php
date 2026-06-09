<?php

namespace App\controller\web;

use App\middleware\WebAuthMiddleware;
use App\helpers\PermissionHelper;
use App\service\HolidayService;


class DashboardController
{

    private $webAuthMiddleware;
    private $holidayService;

    public function __construct()
    {
        $this->webAuthMiddleware = new WebAuthMiddleware;
        $this->holidayService = new HolidayService;
    }

    public function index()
    {
        //Passa pela verificação de COOKIES
        $user = $this->webAuthMiddleware->handle();
        
        //Faz sycronização de feriados Atual
        $this->holidayService->syncYear(date('Y'));
        
        require_once VIEW_PATH . "/dashboard.php";
    }
}


?>