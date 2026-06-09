<?php

namespace App\service;

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

    //Formata data atual
    public function getDateComplete():string
    {
        //Formata data completa
        $day = $this->getCurrentDate();
        $month = $this->getCurrentMonth();
        $year = $this->getCurrentYear();

        return sprintf('%04d-%02d-%02d', $day, $month, $year);
    }


    //Lista de meses traduzidos
    public function getListNameMonth():array
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

}


?>