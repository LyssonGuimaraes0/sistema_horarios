<?php

namespace App\service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public function render(string $view, array $dados)
    {
        extract($dados);

        ob_start();
        include VIEW_PATH . "/{$view}";
        $html = ob_get_clean();

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        header('Content-Type: application/pdf');

        echo $dompdf->output();
    }
}


