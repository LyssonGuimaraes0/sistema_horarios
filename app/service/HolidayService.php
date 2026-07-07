<?php

namespace App\service;

use App\models\CargoModel;
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

    //Coleta de feriados vai API
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

    //Criação de novo feriado
    public function createHoliday($dados)
    {
        //Verifica caso já exista registro
        if ($this->holidayModel->existsDate($dados['date']) === true) {
            throw new \Exception("Data já registrada como Feriado!");
        }

        $this->holidayModel->create($dados);
    }

    //Criação de novo feriado
    public function createOptionalHolidays($dados)
    {
        $horario = $dados['time'];
        //Verifica caso já exista registro
        if ($this->holidayModel->existsDate($dados['date']) === true) {
            throw new \Exception("Data já registrada como Feriado!", 409);
        }

        //Registro na tabela de feriados
        $holiday = [
            'name' => 'Ponto Facultativo',
            'date' => $dados['date']
        ];

        $horariosValidados = [];
        foreach ($horario as $role => $horarios) {

            // Se todos os horários estiverem vazios, ignora esse cargo
            if (count(array_filter($horarios, fn($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            // Se algum horário estiver vazio, lança exceção
            foreach ($horarios as $campo => $valor) {
                if ($valor === null || $valor === '') {
                    throw new \Exception("O cargo '{$role}' possui horários incompletos.");
                }
            }

            // Monta o array final
            if ($role === "Estagiario") {

                $horariosValidados["Estagiario-Manha"] = [
                    "entrada" => $horarios["manha_entrada"],
                    "saida" => $horarios["manha_saida"],
                ];

                $horariosValidados["Estagiario-Tarde"] = [
                    "entrada" => $horarios["tarde_entrada"],
                    "saida" => $horarios["tarde_saida"],
                ];

            } else {

                $horariosValidados[$role] = $horarios;

            }
        }

        foreach ($horariosValidados as $role => $horarios) {

            $idRole = CargoModel::getIdbyRole($role);

            $this->holidayModel->createOptionalHolidays(
                $holiday,
                $idRole,
                $dados,
                $horarios
            );
        }

    }

    //Obter Lista de Feriados
    public function getListHolidays(int $year): array
    {
        $holidays = $this->holidayModel->getHolidays($year);

        return array_column(
            $holidays,
            'feriado',
            'data_completa'
        );
    }

    //Delete de feriado ou ponto facultativo
    public function deleteHoliday(string $date)
    {    
        $this->holidayModel->delete($date);
    }

    //Verifica se a data é feriado
    public function isHoliday(string $date): bool
    {
        return $this->holidayModel->existsDate($date);
    }
}


