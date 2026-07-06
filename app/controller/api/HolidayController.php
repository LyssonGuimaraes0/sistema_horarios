<?php

namespace App\controller\api;

use App\service\HolidayService;
use App\service\DateService;
use App\helpers\PermissionHelper;
use App\middleware\AuthMiddleware;


class HolidayController extends ApiController
{

    private $holidayService;
    private $dateService;
    private $authMiddleware;


    public function __construct()
    {
        $this->holidayService = new HolidayService;
        $this->dateService = new DateService();
        $this->authMiddleware = new AuthMiddleware();

    }

    public function getHolidays()
    {

        try {
            //Coleta ano atual
            $year = $this->dateService->getCurrentYear();

            $holiday = $this->holidayService->getListHolidays($year);

            return $this->success($holiday, 200);

        } catch (\Exception $e) {

            return $this->error($e->getMessage(), 500);
        }

    }
    //Criação de feriado
    public function create()
    {

        try {

            $user = $this->authMiddleware->handle();

            //Verificar se tem permissão de admin
            if (!PermissionHelper::isAdmin($user)) {
                throw new \Exception("Usuario não tem permissão", 401);
            }

            //Coleta ano atual
            $dados = json_decode(file_get_contents('php://input'), true);

            $this->holidayService->createHoliday($dados);

            return $this->success("Feriado Criado com Sucesso", 201);

        } catch (\Exception $e) {

            $statusCode = $e->getCode();

            if ($statusCode === 401) {
                return $this->error($e->getMessage(), 401);
            }

            return $this->error($e->getMessage(), 500);
        }

    }

    //Criação de Ponto facultativo
    public function store()
    {

        try {
            //Coleta ano atual
            $dados = json_decode(file_get_contents('php://input'), true);

            $this->holidayService->createOptionalHolidays($dados);

            return $this->success("Ponto Facultativo Criado com Sucesso", 201);

        } catch (\Exception $e) {

            return $this->error($e->getMessage(), 500);
        }

    }


}
