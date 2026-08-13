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

    // ---------------------------------------------------------------
    // Reportes (reemplazan AvaluosReporteHoja.php y sus variantes)
    // ---------------------------------------------------------------

    /**
     * Lista de avalúos para los reportes "Entregados"/"Solicitados"
     * (con periodo) y "Vencidos"/"Terminados" (status directo, sin fecha).
     *
     * @param int         $tipo Status del reporte: 2 Vencidos, 3 Terminados, 5 Solicitados, 6 Entregados
     * @param string|null $f1   Fecha inicial (Y-m-d), para el modo "por semana"
     * @param string|null $f2   Fecha final (Y-m-d), para el modo "por semana"
     * @param int|null    $ano  Año, para los modos "por mes" y "por año"
     * @param int|null    $mes  Mes (1-12), para el modo "por mes"
     */
    public function reporte(int $tipo, ?string $f1 = null, ?string $f2 = null, ?int $ano = null, ?int $mes = null): array
    {
        $builder = $this->db->table('avaluos A')
            ->select('A.Folio, B.Nombre AS Trabajo, C.Nombre AS Cliente, A.Direccion, A.Colonia')
            ->join('trabajos B', 'B.IdTrabajo = A.IdTrabajo')
            ->join('clientes C', 'C.IdCliente = A.IdCliente')
            ->orderBy('A.Folio', 'DESC');

        if ($tipo === 2 || $tipo === 3) {
            // Vencidos / Terminados: se listan todos, sin acotar por fecha.
            $builder->where('A.Status', $tipo);

            return $builder->get()->getResultArray();
        }

        $campoFecha = $tipo === 6 ? 'A.FechaEntregado' : 'A.FechaSolicitud';
        if ($tipo === 6) {
            $builder->where('A.Status', 6);
        }

        if ($f1 !== null && $f2 !== null) {
            $builder->where("$campoFecha >=", $f1)->where("$campoFecha <=", $f2);
        } elseif ($ano !== null && $mes !== null) {
            $builder->where("YEAR($campoFecha)", $ano)->where("MONTH($campoFecha)", $mes);
        } elseif ($ano !== null) {
            $builder->where("YEAR($campoFecha)", $ano);
        }

        return $builder->get()->getResultArray();
    }

    public function resumenAnual(int $ano): array
    {
        return [
            'solicitados' => $this->where('YEAR(FechaSolicitud)', $ano)->countAllResults(),
            'entregados'  => $this->where('YEAR(FechaEntregado)', $ano)->where('Status', 6)->countAllResults(),
            'terminados'  => $this->where('YEAR(FechaTerminacion)', $ano)->countAllResults(),
            'vencidos'    => $this->where('YEAR(FechaVencimiento)', $ano)->where('Status', 2)->countAllResults(),
        ];
    }

    /** @return list<array{IdTrabajo:int,Nombre:string,solicitados:int,terminados:int,entregados:int,cancelados:int}> */
    public function resumenPorTrabajo(int $ano): array
    {
        $trabajos = $this->db->table('trabajos')->select('IdTrabajo, Nombre')->get()->getResultArray();

        foreach ($trabajos as &$t) {
            $id = $t['IdTrabajo'];
            $t['solicitados'] = $this->where('YEAR(FechaSolicitud)', $ano)->where('IdTrabajo', $id)->countAllResults();
            $t['terminados']  = $this->where('YEAR(FechaTerminacion)', $ano)->where('IdTrabajo', $id)->countAllResults();
            $t['entregados']  = $this->where('YEAR(FechaEntregado)', $ano)->where('IdTrabajo', $id)->where('Status', 6)->countAllResults();
            $t['cancelados']  = $this->where('YEAR(FechaSolicitud)', $ano)->where('IdTrabajo', $id)->where('Status', 1)->countAllResults();
        }

        return $trabajos;
    }
}
