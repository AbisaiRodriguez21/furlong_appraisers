<?php

namespace App\Libraries;

/**
 * Envoltura sobre la librería Cezpdf/Cpdf original (R&OS, dominio público,
 * la misma que usaba el sistema legacy) para generar los reportes en PDF.
 * Reemplaza el patrón repetido en los ~9 archivos *Hoja.php del sistema
 * original: cada uno abría la librería, fijaba fuente y márgenes, armaba
 * una tabla y hacía stream — todo con el mismo código copiado y pegado.
 */
class ReportePdf
{
    private \Cezpdf $pdf;

    public function __construct(string $papel = 'a4')
    {
        $base = APPPATH . 'ThirdParty/Cezpdf/';
        require_once $base . 'class.pdf.php';
        require_once $base . 'class.ezpdf.php';

        $this->pdf = new \Cezpdf($papel);
        $this->pdf->selectFont($base . 'fonts/Helvetica.afm');
        $this->pdf->ezSetCmMargins(1, 1, 1.5, 1.5);
    }

    public function texto(string $texto, int $tamano = 9, array $opciones = []): static
    {
        $this->pdf->ezText($texto, $tamano, $opciones);

        return $this;
    }

    public function textoCentrado(string $texto, int $tamano = 11): static
    {
        return $this->texto($texto, $tamano, ['justification' => 'center']);
    }

    public function textoDerecha(string $texto, int $tamano = 11): static
    {
        return $this->texto($texto, $tamano, ['justification' => 'right']);
    }

    /**
     * @param list<array<string, mixed>> $datos  Debe ser una variable real,
     *        no un literal — Cpdf recibe la tabla por referencia.
     */
    public function tabla(array $datos, array $titulos, array $opciones = []): static
    {
        $opcionesFinales = array_merge([
            'shadeCol'     => [1, 1, 1],
            'xOrientation' => 'center',
            'width'        => 550,
        ], $opciones);

        $this->pdf->ezTable($datos, $titulos, '', $opcionesFinales);

        return $this;
    }

    public function encabezadoFechaHora(): static
    {
        return $this->texto(date('d/m/Y') . '  ' . date('h:i:s a') . "\n", 9);
    }

    public function bytes(): string
    {
        return $this->pdf->ezOutput();
    }
}
