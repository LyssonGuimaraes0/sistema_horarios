<?php

namespace App\service;

use App\models\HolidayModel;

class HolidayService
{

    private $holidayModel;

    public function __construct()
    {
        $this->holidayModel = new HolidayModel;
    }
    public function syncYear(int $year): void
    {

        // Evita buscar novamente se já existir
        if ($this->holidayModel->existsYear($year)) {
            return;
        }

        $holidays = $this->fetchApi($year);

        foreach ($holidays as $holiday) {
            $this->holidayModel->create([
                'date' => $holiday['date'],
                'name' => $holiday['name']
            ]);
        }
    }

    private function fetchApi(int $year): array
    {
        $token = $_ENV['TOKEN_FERIADOS'];

        $url = "https://api.invertexto.com/v1/holidays/$year?token=$token";

        //Prepara conexão HTTP

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resposta = curl_exec($ch);

        //fecha conexão
        curl_close($ch);

        //Converte resposta de JSON para array
        $feriados = json_decode($resposta, true);

        return $feriados;

    }

    public function getListHolidays(int $year): array
    {
        $holidays = $this->holidayModel->getHolidays($year);

        return array_column(
            $holidays,
            'feriado',
            'data_completa'
        );
    }

    //Verifica se a data é feriado
    public function isHoliday(string $date): bool
    {
        return $this->holidayModel->existsDate($date);
    }
}


