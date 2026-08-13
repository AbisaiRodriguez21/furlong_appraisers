<?php

namespace App\Models;

use CodeIgniter\Model;

class DocTrabajoModel extends Model
{
    protected $table            = 'doctrabajos';
    protected $primaryKey       = 'IdDocTrab';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['IdTrabajo', 'IdDocumento', 'Tipo'];

    /**
     * Documentos ya ligados a un trabajo, con su nombre y si son obligatorios.
     */
    public function documentosDe(int $idTrabajo): array
    {
        return $this->select('documentos.IdDocumento, documentos.Nombre, doctrabajos.Tipo')
            ->join('documentos', 'documentos.IdDocumento = doctrabajos.IdDocumento')
            ->where('doctrabajos.IdTrabajo', $idTrabajo)
            ->orderBy('documentos.Nombre', 'ASC')
            ->findAll();
    }

    public function reemplazarDocumentos(int $idTrabajo, array $documentos): void
    {
        $this->where('IdTrabajo', $idTrabajo)->delete();

        foreach ($documentos as $doc) {
            $this->insert([
                'IdTrabajo'   => $idTrabajo,
                'IdDocumento' => $doc['IdDocumento'],
                'Tipo'        => $doc['Tipo'],
            ]);
        }
    }
}
