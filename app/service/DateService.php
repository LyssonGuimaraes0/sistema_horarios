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

    public static function getAllDaysOfMonth(int $year, int $month): int
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

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
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

    //Calcular periodo de entre datas

    public static function getPeriod($dataStart, $dataEnd)
    {

        $inicio = new DateTime($dataStart);
        $fim = new DateTime($dataEnd);

        $intervalo = new \DateInterval('P1D');

        $fim->modify('+1 day');

        // Cria o gerador de período
        $periodo = new \DatePeriod($inicio, $intervalo, $fim);

        // Transforma o período em um array de strings com as datas
       $dateList = [];
        foreach ($periodo as $data) {
            $dateList[] = $data->format('Y-m-d');
        }

        return $dateList;

    }

    //Coleta todas datas do mes

    public function getAllDateOfMonth(int $year, int $month): array
    {
        //Coleta Total de dias
        $allDays = DateService::getAllDaysOfMonth($year, $month);

        $allDateMonth = [];

        //Loop para buscar datas
        for ($day = 1; $day <= $allDays; $day++) {

            $dataAtual = sprintf('%04d-%02d-%02d', $year, $month, $day);

            //Verifica se é final de semana
            $weekDay = date('N', strtotime($dataAtual));
            $isWeekend = ($weekDay > 5) ? true : false;

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