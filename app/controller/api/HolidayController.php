<?php

namespace App\controller\api;

use App\service\HolidayService;
use App\service\DateService;


class HolidayController extends ApiController
{

    private $holidayService;
    private $dateService;


    public function __construct()
    {
        $this->holidayService = new HolidayService;
        $this->dateService = new DateService();

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

    public function create()
    {

        try {
            //Coleta ano atual
            $dados = json_decode(file_get_contents('php://input'), true);

            $this->holidayService->createHoliday($dados);

            return $this->success("Feriado Criado com Sucesso", 201);

        } catch (\Exception $e) {

            return $this->error($e->getMessage(), 500);
        }

    }


}
