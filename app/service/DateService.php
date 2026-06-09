<?php

namespace App\service;

use DateTime;

class DateService
{
    //Coleta dia Atual
    public function getCurrentDate(): int
    {
        return date("d");
    }
    //Coleta Mes atual
    public function getCurrentMonth(): int
    {
        return date("m");
    }
    //Coleta Ano atual
    public function getCurrentYear(): int
    {
        return date("Y");
    }

    //Coleta Total de dias de mes e ano

    public function getAllDaysOfMonth(int $year, int $month): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    //Formata data atual
    public function getDateComplete(): string
    {
        //Formata data completa
        $day = $this->getCurrentDate();
        $month = $this->getCurrentMonth();
        $year = $this->getCurrentYear();

        return sprintf('%04d-%02d-%02d', $day, $month, $year);
    }


    //Lista de meses 
    public function getListNameMonth(): array
    {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];
    }

    //Lista de dias da semana
    public function getListNameWeek(): array
    {
        return [
            1 => 'Segunda-Feira',
            2 => 'Terça-Feira',
            3 => 'Quarta-Feira',
            4 => 'Quinta-Feira',
            5 => 'Sexta-Feira',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
    }

    //Coleta todas datas do mes

    public function getAllDateOfMonth(int $year, int $month): array
    {
        //Coleta Total de dias
        $allDays = $this->getAllDaysOfMonth($year, $month);

        $allDateMonth = [];


        //Loop para buscar datas
        for ($day = 1; $day <= $allDays; $day++) {

            $dataAtual = sprintf('%04d-%02d-%02d', $year, $month, $day);

            //Verifica se é final de semana
            $weekDay = date('N', strtotime($dataAtual));
            $isWeekend = ($weekDay > 4) ? true : false;

            //Coleta nome do mes
            $listWeek = $this->getListNameWeek();
            $weekName = $listWeek[$weekDay];

            $allDateMonth[] = [
                "date" => $dataAtual,
                "weekName" => $weekName,
                "weekend" => $isWeekend,
            ];

        }

        return $allDateMonth;

    }


}


?>