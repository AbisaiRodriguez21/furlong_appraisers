<?php

namespace App\Controllers;

use App\Libraries\ReportePdf;
use App\Models\AvaluoModel;
use App\Models\SemanaModel;

/**
 * Replaces AvaluosReportes.php, AvaluosReportesLista.php, AvaluosReporteHoja.php,
 * AvaluosReportesGral.php, AvaluosGralHoja.php, AvaluosReportesGralTrabajo.php
 * y AvaluosGralTrabajoHoja.php.
 */
class ReportesAvaluos extends BaseController
{
    private const NOMBRES = [2 => 'VENCIDOS', 3 => 'TERMINADOS', 5 => 'SOLICITADOS', 6 => 'ENTREGADOS'];

    protected AvaluoModel $avaluos;

    public function __construct()
    {
        $this->avaluos = new AvaluoModel();
    }

    public function index()
    {
        return view('reportes/avaluos_index', ['titulo' => 'Reportes de Avalúos']);
    }

    /** Picker de periodo (tipos 5/6), atajo directo (2/3), o picker general (4). */
    public function picker(int $tipo)
    {
        if ($tipo === 2 || $tipo === 3) {
            return redirect()->to(site_url('avaluos/reportes/lista/' . $tipo));
        }

        if ($tipo === 4) {
            return view('reportes/avaluos_general_picker', [
                'titulo' => 'Reporte General de Avalúos',
            ]);
        }

        return view('reportes/avaluos_picker', [
            'titulo'  => 'Reporte de Avalúos ' . self::NOMBRES[$tipo],
            'tipo'    => $tipo,
            'semanas' => (new SemanaModel())->listar(),
        ]);
    }

    /** Vista previa en pantalla, antes de imprimir (tipos 2/3/5/6). */
    public function lista()
    {
        [$tipo, $f1, $f2, $ano, $mes, $opcion] = $this->leerCriterios($this->request->getPost());
        $filas = $this->avaluos->reporte($tipo, $f1, $f2, $ano, $mes);

        return view('reportes/avaluos_lista', [
            'titulo' => 'Reporte de Avalúos ' . self::NOMBRES[$tipo],
            'tipo'   => $tipo,
            'filas'  => $filas,
            'opcion' => $opcion,
            'f1'     => $f1,
            'f2'     => $f2,
            'ano'    => $ano,
            'mes'    => $mes,
        ]);
    }

    public function listaDirecta(int $tipo)
    {
        $filas = $this->avaluos->reporte($tipo);

        return view('reportes/avaluos_lista', [
            'titulo' => 'Reporte de Avalúos ' . self::NOMBRES[$tipo],
            'tipo'   => $tipo,
            'filas'  => $filas,
            'opcion' => 0,
            'f1'     => null,
            'f2'     => null,
            'ano'    => null,
            'mes'    => null,
        ]);
    }

    public function pdf()
    {
        [$tipo, $f1, $f2, $ano, $mes, $opcion] = $this->leerCriterios($this->request->getPost());
        $filas = $this->avaluos->reporte($tipo, $f1, $f2, $ano, $mes);

        $meses = [1 => 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $subtitulo = match ($opcion) {
            1       => 'Del: ' . date('d/m/Y', strtotime($f1)) . ' Al: ' . date('d/m/Y', strtotime($f2)),
            2       => $meses[$mes] . ' DEL ' . $ano,
            3       => 'AÑO ' . $ano,
            default => '',
        };

        $data = [];
        foreach ($filas as $f) {
            $data[] = ['0' => $f['Folio'], '1' => $f['Trabajo'], '2' => $f['Cliente'], '3' => $f['Direccion'], '4' => $f['Colonia']];
        }

        $pdf = new ReportePdf();
        $pdf->textoCentrado('REPORTE DE AVALUOS ' . self::NOMBRES[$tipo] . "\n", 12)
            ->texto('Total de Avaluos: ' . count($filas), 9)
            ->textoDerecha(date('d/m/Y') . '  ' . date('h:i:s a'), 8);
        if ($subtitulo !== '') {
            $pdf->texto($subtitulo . "\n", 9);
        } else {
            $pdf->texto("\n", 4);
        }
        $pdf->tabla($data, ['0' => '<b>FOLIO</b>', '1' => '<b>TRABAJO REALIZADO</b>', '2' => '<b>CLIENTE</b>', '3' => '<b>DIRECCION</b>', '4' => '<b>COLONIA</b>'], [
                'width' => 570,
                'fontSize' => 8,
                'cols' => ['0' => ['width' => 40], '1' => ['width' => 150], '2' => ['width' => 130], '3' => ['width' => 130], '4' => ['width' => 120]],
            ]);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    public function generalPorStatus(int $ano)
    {
        return view('reportes/avaluos_general', ['titulo' => 'Reporte General de Avalúos', 'ano' => $ano, 'resumen' => $this->avaluos->resumenAnual($ano)]);
    }

    public function generalPorStatusPdf()
    {
        $ano     = (int) $this->request->getPost('a');
        $resumen = $this->avaluos->resumenAnual($ano);

        $data = [
            ['0' => 'Avaluos Solicitados:', '1' => $resumen['solicitados']],
            ['0' => 'Avaluos Entregados:', '1' => $resumen['entregados']],
            ['0' => 'Avaluos Terminados:', '1' => $resumen['terminados']],
            ['0' => 'Avaluos Vencidos:', '1' => $resumen['vencidos']],
        ];

        $pdf = new ReportePdf();
        $pdf->texto(date('d/m/Y') . '  ' . date('h:i:s a') . "\n", 12)
            ->textoCentrado('REPORTE AVALUOS DEL AÑO ' . $ano . "\n", 12)
            ->tabla($data, ['0' => '', '1' => ''], [
                'width' => 200, 'fontSize' => 12, 'showLines' => 0,
                'cols' => ['0' => ['width' => 150, 'justification' => 'right'], '1' => ['width' => 50, 'justification' => 'center']],
            ]);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    public function generalPorTrabajo(int $ano)
    {
        return view('reportes/avaluos_general_trabajo', ['titulo' => 'Reporte de Avalúos por Trabajo', 'ano' => $ano, 'filas' => $this->avaluos->resumenPorTrabajo($ano)]);
    }

    public function generalPorTrabajoPdf()
    {
        $ano   = (int) $this->request->getPost('a');
        $filas = $this->avaluos->resumenPorTrabajo($ano);

        $data = [];
        foreach ($filas as $f) {
            $data[] = ['0' => $f['Nombre'], '1' => $f['solicitados'], '2' => $f['terminados'], '3' => $f['entregados'], '4' => $f['cancelados']];
        }

        $pdf = new ReportePdf();
        $pdf->texto(date('d/m/Y') . '  ' . date('h:i:s a') . "\n", 12)
            ->textoCentrado('REPORTE AVALUOS POR TRABAJO DEL AÑO ' . $ano . "\n", 12)
            ->tabla($data, ['0' => '', '1' => 'Solicitados', '2' => 'Terminados', '3' => 'Entregados', '4' => 'Cancelados'], [
                'width' => 550, 'fontSize' => 12, 'showLines' => 1,
                'cols' => ['0' => ['width' => 255, 'justification' => 'left'], '1' => ['width' => 70, 'justification' => 'center'], '2' => ['width' => 75, 'justification' => 'center']],
            ]);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    /** @return array{0:int,1:?string,2:?string,3:?int,4:?int,5:int} [tipo, f1, f2, ano, mes, opcion] */
    private function leerCriterios(array $post): array
    {
        $tipo   = (int) $post['id'];
        $opcion = (int) ($post['radio'] ?? $post['Opc'] ?? 0);
        $f1 = $f2 = null;
        $ano = $mes = null;

        if ($opcion === 1) {
            $semana = (new SemanaModel())->find((int) ($post['semanas'] ?? $post['Fecha1'] ?? 0));
            if (isset($post['Fecha1'])) {
                $f1 = $post['Fecha1'];
                $f2 = $post['Fecha2'];
            } elseif ($semana) {
                $f1 = $semana['FechaIni'];
                $f2 = $semana['FechaFin'];
            }
        } elseif ($opcion === 2) {
            $ano = (int) ($post['Anio'] ?? 0);
            $mes = (int) ($post['Mes'] ?? 0);
        } elseif ($opcion === 3) {
            $ano = (int) ($post['Anio2'] ?? $post['Anio'] ?? 0);
        }

        return [$tipo, $f1, $f2, $ano, $mes, $opcion];
    }
}
