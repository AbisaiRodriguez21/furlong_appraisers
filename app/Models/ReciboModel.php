<?php

namespace App\Models;

use CodeIgniter\Model;

class ReciboModel extends Model
{
    protected $table            = 'recibos';
    protected $primaryKey       = 'NoFolio';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'NoFolio', 'Fecha', 'IdCliente', 'Concepto', 'Cantidad', 'Honorarios', 'Iva',
        'Subtotal', 'RetISR', 'RetIVA', 'Status', 'MotivoC', 'MetodoPago', 'CtaBanco',
    ];

    // El esquema SICOFI del recibo solo cubre folios 501-1200 (dos bloques de sello
    // distintos), igual que el sistema original.
    public const FOLIO_MAXIMO = 1200;

    public function siguienteFolio(): int
    {
        $max = $this->selectMax('NoFolio')->first();

        return $max['NoFolio'] !== null ? ((int) $max['NoFolio']) + 1 : 501;
    }

    public function listar(): array
    {
        return $this->db->table('recibos A')
            ->select('A.NoFolio, A.Fecha, B.Nombre AS Cliente, A.Concepto, A.Cantidad, A.Status')
            ->join('clientes B', 'B.IdCliente = A.IdCliente')
            ->orderBy('A.NoFolio', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function conCliente(int $folio): ?array
    {
        return $this->db->table('recibos A')
            ->select('A.*, B.Nombre AS NombreCliente, B.Direccion, B.RFC')
            ->join('clientes B', 'B.IdCliente = A.IdCliente')
            ->where('A.NoFolio', $folio)
            ->get()
            ->getRowArray();
    }

    public function cancelar(int $folio, string $motivo): void
    {
        $this->update($folio, ['Status' => '1', 'MotivoC' => $motivo]);
    }
}
