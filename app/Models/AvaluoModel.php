<?php

namespace App\Models;

use CodeIgniter\Model;

class AvaluoModel extends Model
{
    protected $table            = 'avaluos';
    protected $primaryKey       = 'Folio';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'Folio', 'FechaSolicitud', 'NombreInspeccion', 'FechaInspeccion', 'FechaVencimiento',
        'FechaEntregado', 'IdTrabajo', 'IdCliente', 'Solicitante', 'Propietario', 'Direccion',
        'Colonia', 'Ciudad', 'Estado', 'NombreArchivo', 'ValorAvaluo', 'ValorReal', 'Honorarios',
        'Img', 'Status', 'Comentario', 'Tipo', 'FechaTerminacion',
    ];

    // Estatus visibles en el listado y su color, igual que el sistema original.
    public const ESTATUS = [
        1 => ['nombre' => 'Cancelado', 'color' => '#FF2828'],
        2 => ['nombre' => 'Vencido', 'color' => '#BC7D3F'],
        3 => ['nombre' => 'Terminado', 'color' => '#4D9999'],
        5 => ['nombre' => 'En Proceso', 'color' => '#8DDA87'],
        6 => ['nombre' => 'Entregado', 'color' => '#BAE1FE'],
        7 => ['nombre' => 'Pendiente', 'color' => '#F9FD64'],
    ];

    public function listar(int $ano, int $idTrabajo = 0, int $status = 0): array
    {
        $builder = $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, A.Status, A.Direccion, A.Colonia')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->where('YEAR(A.FechaSolicitud)', $ano)
            ->orderBy('YEAR(A.FechaSolicitud)', 'DESC')
            ->orderBy('A.Folio', 'DESC');

        if ($idTrabajo !== 0) {
            $builder->where('A.IdTrabajo', $idTrabajo);
        }
        if ($status !== 0) {
            $builder->where('A.Status', $status);
        }

        return $builder->get()->getResultArray();
    }

    public function conNombres(string $folio): ?array
    {
        return $this->db->table('avaluos A')
            ->select('A.*, Cl.Nombre AS NombreCliente, Sol.Nombre AS NombreSolicitante, Prop.Nombre AS NombrePropietario, T.Nombre AS NombreTrabajo')
            ->join('clientes Cl', 'Cl.IdCliente = A.IdCliente')
            ->join('clientes Sol', 'Sol.IdCliente = A.Solicitante')
            ->join('clientes Prop', 'Prop.IdCliente = A.Propietario')
            ->join('trabajos T', 'T.IdTrabajo = A.IdTrabajo')
            ->where('A.Folio', $folio)
            ->get()
            ->getRowArray();
    }

    /**
     * Folio siguiente para el año dado, formato NNNN-YY (igual al original:
     * consecutivo de 4 dígitos + los 2 últimos dígitos del año).
     */
    public function siguienteFolio(int $anoCompleto): string
    {
        $yy = substr((string) $anoCompleto, -2);

        $ultimo = $this->select('Folio')
            ->like('Folio', '-' . $yy, 'before')
            ->orderBy('Folio', 'DESC')
            ->first();

        $consecutivo = $ultimo ? ((int) explode('-', $ultimo['Folio'])[0]) + 1 : 1;

        return str_pad((string) $consecutivo, 4, '0', STR_PAD_LEFT) . '-' . $yy;
    }

    public function cancelar(string $folio): void
    {
        $this->update($folio, [
            'Status'           => '1',
            'FechaEntregado'   => null,
            'FechaTerminacion' => null,
        ]);
    }
}
