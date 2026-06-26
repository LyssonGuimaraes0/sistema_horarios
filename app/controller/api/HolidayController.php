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


}
