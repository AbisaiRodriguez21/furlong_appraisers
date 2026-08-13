<?php

namespace App\Models;

use CodeIgniter\Model;

class CobranzaModel extends Model
{
    protected $table            = 'cobranza';
    protected $primaryKey       = 'IdPago';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['Fecha', 'Importe', 'Folio'];

    /**
     * Avalúos del año con su saldo pendiente (Honorarios - suma de pagos).
     * $filtro: 0=Todos, 1=Adeudos (saldo != 0), 2=Pagados (saldo == 0).
     */
    public function listar(int $ano, int $filtro = 1): array
    {
        $filas = $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, A.Direccion, A.Colonia, A.Honorarios, A.Tipo')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->where('A.Status !=', 1)
            ->where('YEAR(A.FechaSolicitud)', $ano)
            ->orderBy('A.Folio', 'DESC')
            ->get()
            ->getResultArray();

        $resultado = [];
        foreach ($filas as $fila) {
            $pagado = (float) ($this->selectSum('Importe')->where('Folio', $fila['Folio'])->first()['Importe'] ?? 0);
            $saldo  = round((float) $fila['Honorarios'] - $pagado, 2);

            if ($filtro === 1 && $saldo == 0.0) {
                continue;
            }
            if ($filtro === 2 && $saldo != 0.0) {
                continue;
            }

            $fila['Saldo'] = $saldo;
            $resultado[]   = $fila;
        }

        return $resultado;
    }

    public function datosAvaluo(string $folio): ?array
    {
        return $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, A.Honorarios')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->where('A.Folio', $folio)
            ->get()
            ->getRowArray();
    }

    public function saldoDe(string $folio, float $honorarios): float
    {
        $pagado = (float) ($this->selectSum('Importe')->where('Folio', $folio)->first()['Importe'] ?? 0);

        return round($honorarios - $pagado, 2);
    }

    public function pagosDe(string $folio): array
    {
        return $this->where('Folio', $folio)->orderBy('IdPago', 'ASC')->findAll();
    }

    public function registrarPago(string $folio, float $importe): void
    {
        $this->insert(['Fecha' => date('Y-m-d'), 'Importe' => $importe, 'Folio' => $folio]);
    }

    // ---------------------------------------------------------------
    // Reportes (reemplazan CobranzaInfo/CobranzaInfoCliente/CobranzaReporte*Hoja.php)
    // ---------------------------------------------------------------

    /** Pagos registrados en una fecha exacta (reporte "Pagos del día"). */
    public function pagosDelDia(string $fecha): array
    {
        return $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, D.Importe')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->join('cobranza D', 'D.Folio = A.Folio')
            ->where('D.Fecha', $fecha)
            ->orderBy('A.Folio', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Pagos o adeudos de un cliente, por avalúo (reporte "por Cliente").
     * $tipo: 0 = pagos (incluye columna Pago), 1 = solo adeudos pendientes.
     */
    public function porCliente(int $idCliente, int $tipo): array
    {
        $avaluos = $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, A.Honorarios, A.Tipo')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->where('A.IdCliente', $idCliente)
            ->groupBy('A.Folio')
            ->orderBy('A.Folio', 'DESC')
            ->get()
            ->getResultArray();

        $filas = [];
        foreach ($avaluos as $a) {
            $pago   = (float) ($this->selectSum('Importe')->where('Folio', $a['Folio'])->first()['Importe'] ?? 0);
            $adeudo = round((float) $a['Honorarios'] - $pago, 2);

            if ($adeudo == 0.0) {
                continue;
            }

            $a['Pago']   = $pago;
            $a['Adeudo'] = $adeudo;
            $filas[]     = $a;
        }

        return $filas;
    }

    /**
     * Reporte general de pagos/adeudos por periodo.
     * $tipo: 0 = pagos del periodo, 1 = adeudos pendientes de avalúos con
     * al menos un pago en el periodo (mismo criterio que el original).
     */
    public function general(int $tipo, ?string $f1 = null, ?string $f2 = null, ?int $ano = null, ?int $mes = null): array
    {
        $builder = $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, A.Honorarios, A.Tipo')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->join('cobranza D', 'D.Folio = A.Folio')
            ->groupBy('A.Folio')
            ->orderBy('A.Folio', 'DESC');

        if ($f1 !== null && $f2 !== null) {
            $builder->where('D.Fecha >=', $f1)->where('D.Fecha <=', $f2);
        } elseif ($ano !== null && $mes !== null) {
            $builder->where('YEAR(D.Fecha)', $ano)->where('MONTH(D.Fecha)', $mes);
        } elseif ($ano !== null) {
            $builder->where('YEAR(D.Fecha)', $ano);
        }

        $avaluos = $builder->get()->getResultArray();

        $filas = [];
        foreach ($avaluos as $a) {
            $pago     = (float) ($this->selectSum('Importe')->where('Folio', $a['Folio'])->first()['Importe'] ?? 0);
            $cantidad = $tipo === 0 ? $pago : round((float) $a['Honorarios'] - $pago, 2);

            if ($cantidad == 0.0) {
                continue;
            }

            $a['Cantidad'] = $cantidad;
            $filas[]       = $a;
        }

        return $filas;
    }

    /** Pagos de un trabajo específico en un año (reporte "por Trabajo"). */
    public function porTrabajoAno(int $idTrabajo, int $ano): array
    {
        return $this->db->table('avaluos A')
            ->select('A.Folio, C.Nombre AS Cliente, SUM(D.Importe) AS Importe')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->join('cobranza D', 'D.Folio = A.Folio')
            ->where('A.IdTrabajo', $idTrabajo)
            ->where('YEAR(D.Fecha)', $ano)
            ->groupBy('A.Folio')
            ->orderBy('A.Folio', 'DESC')
            ->get()
            ->getResultArray();
    }
}
