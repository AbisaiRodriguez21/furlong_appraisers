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
}
