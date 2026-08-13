<?php

namespace App\Models;

use CodeIgniter\Model;

class AvaluoDocModel extends Model
{
    protected $table            = 'avaluodoc';
    protected $primaryKey       = 'IdAvDoc';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['Folio', 'IdDocumento', 'IdUsuario', 'FechaRecibido', 'StatusR'];

    /**
     * Checklist completo de un avalúo, con quién y cuándo recibió cada
     * documento (para la pantalla de solo consulta).
     */
    public function checklistCompleto(string $folio): array
    {
        return $this->db->table('avaluodoc B')
            ->select('A.Nombre, U.Nombre AS Usuario, B.FechaRecibido, B.StatusR')
            ->join('documentos A', 'A.IdDocumento = B.IdDocumento')
            ->join('usuarios U', 'U.IdUsuario = B.IdUsuario')
            ->where('B.Folio', $folio)
            ->get()
            ->getResultArray();
    }

    /**
     * Documentos que aún faltan por recibir, con si son obligatorios
     * (Tipo, de doctrabajos) para el trabajo actual del avalúo.
     */
    public function pendientes(string $folio, int $idTrabajo): array
    {
        return $this->db->table('avaluodoc B')
            ->select('A.IdDocumento, A.Nombre, C.Tipo')
            ->join('documentos A', 'A.IdDocumento = B.IdDocumento')
            ->join('doctrabajos C', 'C.IdDocumento = A.IdDocumento AND C.IdTrabajo = ' . (int) $idTrabajo)
            ->where('B.Folio', $folio)
            ->where('B.StatusR', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * Crea el checklist de un avalúo nuevo: una fila por cada documento que
     * exige el trabajo elegido. Los que ya vienen marcados como recibidos
     * en el alta quedan con StatusR=1 y fecha de hoy.
     */
    public function crearChecklist(string $folio, int $idTrabajo, array $idsRecibidos, int $idUsuario): void
    {
        $requeridos = $this->db->table('doctrabajos')
            ->select('IdDocumento')
            ->where('IdTrabajo', $idTrabajo)
            ->get()
            ->getResultArray();

        foreach ($requeridos as $doc) {
            $recibido = in_array((int) $doc['IdDocumento'], $idsRecibidos, true);
            $this->insert([
                'Folio'         => $folio,
                'IdDocumento'   => $doc['IdDocumento'],
                'IdUsuario'     => $idUsuario,
                'FechaRecibido' => $recibido ? date('Y-m-d') : null,
                'StatusR'       => $recibido ? 1 : 0,
            ]);
        }
    }

    /**
     * El trabajo del avalúo cambió: se descarta el checklist anterior y se
     * arma uno nuevo (todo pendiente) para los documentos del trabajo nuevo.
     */
    public function reemplazarPorCambioDeTrabajo(string $folio, int $idTrabajo, int $idUsuario): void
    {
        $this->where('Folio', $folio)->delete();

        $requeridos = $this->db->table('doctrabajos')
            ->select('IdDocumento')
            ->where('IdTrabajo', $idTrabajo)
            ->get()
            ->getResultArray();

        foreach ($requeridos as $doc) {
            $this->insert([
                'Folio'         => $folio,
                'IdDocumento'   => $doc['IdDocumento'],
                'IdUsuario'     => $idUsuario,
                'FechaRecibido' => date('Y-m-d'),
                'StatusR'       => 0,
            ]);
        }
    }

    /**
     * Marca como recibidos (hoy, por el usuario en turno) los documentos
     * pendientes cuyo Id venga en $idsMarcados; el resto sigue pendiente.
     */
    public function marcarRecibidos(string $folio, array $idsMarcados, int $idUsuario): void
    {
        if ($idsMarcados === []) {
            return;
        }

        $this->where('Folio', $folio)
            ->where('StatusR', 0)
            ->whereIn('IdDocumento', $idsMarcados)
            ->set([
                'IdUsuario'     => $idUsuario,
                'FechaRecibido' => date('Y-m-d'),
                'StatusR'       => 1,
            ])
            ->update();
    }
}
