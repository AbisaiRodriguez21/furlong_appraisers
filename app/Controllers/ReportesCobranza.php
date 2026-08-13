<?php

namespace App\Controllers;

use App\Libraries\ReportePdf;
use App\Models\CobranzaModel;
use App\Models\SemanaModel;
use App\Models\TrabajoModel;

/**
 * Replaces CobranzaReporte.php, CobranzaReporteHoja.php, CobranzaInfo.php,
 * CobranzaReporteClientes.php, CobranzaReporteClientesHoja.php,
 * CobranzaInfoCliente.php, CobranzaReporteGeneral.php,
 * CobranzaReporteGeneralLista.php, CobranzaReporteGeneralHoja.php,
 * CobranzaReporteTrabajo.php, CobranzaReporteTrabajoLista.php y
 * CobranzaReporteTrabajoHoja.php.
 */
class ReportesCobranza extends BaseController
{
    protected CobranzaModel $cobranza;

    public function __construct()
    {
        $this->cobranza = new CobranzaModel();
    }

    public function index()
    {
        return view('reportes/cobranza_index', ['titulo' => 'Reportes de Cobranza']);
    }

    // ---------------------------------------------------------------
    // Pagos del día
    // ---------------------------------------------------------------

    public function dia()
    {
        return view('reportes/cobranza_dia', ['titulo' => 'Reporte de Cobranza', 'fecha' => date('Y-m-d')]);
    }

    public function diaDatos()
    {
        $fecha = $this->request->getGet('fecha') ?: date('Y-m-d');
        $filas = $this->cobranza->pagosDelDia($fecha);

        return view('reportes/cobranza_dia_tabla', ['filas' => $filas]);
    }

    public function diaPdf()
    {
        $fecha = $this->request->getPost('fecha') ?: date('Y-m-d');
        $filas = $this->cobranza->pagosDelDia($fecha);

        $data  = [];
        $total = 0;
        foreach ($filas as $f) {
            $total += (float) $f['Importe'];
            $data[] = ['0' => $f['Folio'], '1' => $f['Trabajo'], '2' => $f['Cliente'], '3' => '$' . number_format((float) $f['Importe'], 2)];
        }

        $pdf = new ReportePdf();
        $pdf->encabezadoFechaHora()
            ->textoCentrado('REPORTE DE PAGOS DEL DIA: ' . date('d/m/Y', strtotime($fecha)) . "\n", 11)
            ->tabla($data, ['0' => '<b>FOLIO</b>', '1' => '<b>TRABAJO</b>', '2' => '<b>CLIENTE</b>', '3' => '<b>MONTO</b>'], [
                'width' => 550,
                'cols'  => ['0' => ['justification' => 'center', 'width' => 50], '1' => ['justification' => 'left', 'width' => 240], '2' => ['justification' => 'left', 'width' => 200], '3' => ['justification' => 'right', 'width' => 60]],
            ])
            ->textoDerecha("\nMONTO TOTAL DE PAGOS: $" . number_format($total, 2), 11);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    // ---------------------------------------------------------------
    // Por Cliente (pagos o adeudos pendientes)
    // ---------------------------------------------------------------

    public function porCliente(int $tipo)
    {
        return view('reportes/cobranza_por_cliente', ['titulo' => 'Reporte de Cobranza por Cliente', 'tipo' => $tipo]);
    }

    public function porClienteDatos()
    {
        $idCliente = (int) $this->request->getGet('id');
        $tipo      = (int) $this->request->getGet('tipo');
        $filas     = $this->cobranza->porCliente($idCliente, $tipo);

        return view('reportes/cobranza_por_cliente_tabla', ['filas' => $filas, 'tipo' => $tipo]);
    }

    public function porClientePdf()
    {
        $idCliente = (int) $this->request->getPost('IdCliente');
        $tipo      = (int) $this->request->getPost('Tipo');
        $cliente   = (string) $this->request->getPost('Cliente');
        $filas     = $this->cobranza->porCliente($idCliente, $tipo);

        $data = [];
        foreach ($filas as $f) {
            $fila = ['0' => $f['Folio'], '1' => $f['Trabajo']];
            if ($tipo === 0) {
                $fila['2'] = '$ ' . number_format($f['Pago'], 2);
                $fila['3'] = '$ ' . number_format($f['Adeudo'], 2);
            } else {
                $fila['2'] = '$ ' . number_format($f['Adeudo'], 2);
            }
            $data[] = $fila;
        }

        $titulos = $tipo === 0
            ? ['0' => '<b>FOLIO</b>', '1' => '<b>TRABAJO</b>', '2' => '<b>PAGOS</b>', '3' => '<b>ADEUDOS</b>']
            : ['0' => '<b>FOLIO</b>', '1' => '<b>TRABAJO</b>', '2' => '<b>ADEUDOS</b>'];

        $pdf = new ReportePdf();
        $pdf->encabezadoFechaHora()
            ->textoCentrado($tipo === 0 ? 'REPORTE DE PAGOS POR CLIENTE' : 'REPORTE DE COBRANZA PENDIENTE POR CLIENTE', 11)
            ->textoCentrado($cliente . "\n", 11)
            ->tabla($data, $titulos, [
                'width' => 550,
                'cols'  => ['0' => ['justification' => 'center', 'width' => 50], '1' => ['justification' => 'left', 'width' => 380], '2' => ['justification' => 'right', 'width' => 60], '3' => ['justification' => 'right', 'width' => 60]],
            ]);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    // ---------------------------------------------------------------
    // General (pagos o adeudos, por semana/mes-año/año)
    // ---------------------------------------------------------------

    public function general(int $tipo)
    {
        return view('reportes/cobranza_general_picker', [
            'titulo'  => 'Reporte de Cobranza' . ($tipo === 1 ? ' Pendiente' : ''),
            'tipo'    => $tipo,
            'semanas' => (new SemanaModel())->listar(),
        ]);
    }

    public function generalLista()
    {
        [$tipo, $f1, $f2, $ano, $mes, $opcion] = $this->leerCriteriosGeneral($this->request->getPost());
        $filas = $this->cobranza->general($tipo, $f1, $f2, $ano, $mes);

        return view('reportes/cobranza_general_lista', [
            'titulo'    => 'Reporte de Cobranza' . ($tipo === 1 ? ' Pendiente' : ''),
            'tipo'      => $tipo,
            'filas'     => $filas,
            'opcion'    => $opcion,
            'f1'        => $f1,
            'f2'        => $f2,
            'ano'       => $ano,
            'mes'       => $mes,
            'semanaId'  => (int) ($this->request->getPost('semanas') ?? 0),
        ]);
    }

    public function generalPdf()
    {
        [$tipo, $f1, $f2, $ano, $mes, $opcion] = $this->leerCriteriosGeneral($this->request->getPost());
        $filas = $this->cobranza->general($tipo, $f1, $f2, $ano, $mes);

        $meses = [1 => 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        $titulo = match ($opcion) {
            1 => 'REPORTE COBRANZA DEL ' . date('d/m/Y', strtotime($f1)) . ' AL ' . date('d/m/Y', strtotime($f2)),
            2 => 'REPORTE DE COBRANZA ' . $meses[$mes] . ' DEL ' . $ano,
            3 => 'REPORTE DE COBRANZA AÑO ' . $ano,
            default => 'REPORTE DE COBRANZA',
        };

        $data  = [];
        $total = 0;
        foreach ($filas as $f) {
            $total += $f['Cantidad'];
            $data[] = ['0' => $f['Folio'], '1' => $f['Trabajo'], '2' => $f['Cliente'], '3' => '$' . number_format($f['Cantidad'], 2)];
        }

        $pdf = new ReportePdf();
        $pdf->encabezadoFechaHora()
            ->textoCentrado($titulo . "\n", 11)
            ->tabla($data, ['0' => '<b>FOLIO</b>', '1' => '<b>TRABAJO</b>', '2' => '<b>CLIENTE</b>', '3' => '<b>MONTO</b>'], [
                'width' => 550,
                'cols'  => ['0' => ['justification' => 'center', 'width' => 50], '1' => ['justification' => 'left', 'width' => 240], '2' => ['justification' => 'left', 'width' => 200], '3' => ['justification' => 'right', 'width' => 60]],
            ])
            ->textoDerecha("\n" . ($tipo === 0 ? 'MONTO TOTAL DE PAGOS: $' : 'MONTO TOTAL DE ADEUDOS: $') . number_format($total, 2), 11);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    // ---------------------------------------------------------------
    // Por Trabajo + Año
    // ---------------------------------------------------------------

    public function porTrabajo()
    {
        return view('reportes/cobranza_por_trabajo_picker', [
            'titulo'   => 'Reporte de Cobranza por Trabajo',
            'trabajos' => (new TrabajoModel())->activos(),
        ]);
    }

    public function porTrabajoLista()
    {
        $idTrabajo = (int) $this->request->getPost('Trabajo');
        $ano       = (int) $this->request->getPost('Anio');
        $trabajo   = (new TrabajoModel())->find($idTrabajo);
        $filas     = $this->cobranza->porTrabajoAno($idTrabajo, $ano);

        return view('reportes/cobranza_por_trabajo_lista', [
            'titulo'     => 'Reporte de Cobranza por Trabajo',
            'idTrabajo'  => $idTrabajo,
            'ano'        => $ano,
            'nombreTrab' => $trabajo['Nombre'] ?? '',
            'filas'      => $filas,
        ]);
    }

    public function porTrabajoPdf()
    {
        $idTrabajo = (int) $this->request->getPost('IdTrabajo');
        $ano       = (int) $this->request->getPost('Anio');
        $trabajo   = (new TrabajoModel())->find($idTrabajo);
        $filas     = $this->cobranza->porTrabajoAno($idTrabajo, $ano);

        $data  = [];
        $total = 0;
        foreach ($filas as $f) {
            $total += (float) $f['Importe'];
            $data[] = ['0' => $f['Folio'], '1' => $f['Cliente'], '2' => '$' . number_format((float) $f['Importe'], 2)];
        }

        $pdf = new ReportePdf();
        $pdf->encabezadoFechaHora()
            ->textoCentrado('REPORTE DE COBRANZA DEL AÑO ' . $ano . "\n" . ($trabajo['Nombre'] ?? ''), 11)
            ->tabla($data, ['0' => '<b>FOLIO</b>', '1' => '<b>CLIENTE</b>', '2' => '<b>MONTO</b>'], [
                'width' => 550,
                'cols'  => ['0' => ['justification' => 'center', 'width' => 50], '1' => ['justification' => 'left', 'width' => 430], '2' => ['justification' => 'right', 'width' => 70]],
            ])
            ->textoDerecha("\nMONTO TOTAL DE PAGOS: $" . number_format($total, 2), 11);

        return $this->response->setContentType('application/pdf')->setBody($pdf->bytes());
    }

    /** @return array{0:int,1:?string,2:?string,3:?int,4:?int,5:int} [tipo, f1, f2, ano, mes, opcion] */
    private function leerCriteriosGeneral(array $post): array
    {
        $tipo   = (int) ($post['Tipo'] ?? 0);
        $opcion = (int) ($post['radio'] ?? $post['Opc'] ?? 0);
        $f1 = $f2 = null;
        $ano = $mes = null;

        if ($opcion === 1) {
            $semana = (new SemanaModel())->find((int) ($post['semanas'] ?? 0));
            $f1 = $semana['FechaIni'] ?? null;
            $f2 = $semana['FechaFin'] ?? null;
        } elseif ($opcion === 2) {
            $ano = (int) ($post['Anio'] ?? 0);
            $mes = (int) ($post['Mes'] ?? 0);
        } elseif ($opcion === 3) {
            $ano = (int) ($post['Anio2'] ?? $post['Anio'] ?? 0);
        }

        return [$tipo, $f1, $f2, $ano, $mes, $opcion];
    }
}
