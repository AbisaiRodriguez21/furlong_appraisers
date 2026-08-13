<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentoModel extends Model
{
    protected $table            = 'documentos';
    protected $primaryKey       = 'IdDocumento';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['Nombre', 'Status'];

    public function activos(): array
    {
        return $this->where('Status', '1')->orderBy('Nombre', 'ASC')->findAll();
    }

    public function porEstado(string $status): array
    {
        return $this->where('Status', $status)->orderBy('Nombre', 'ASC')->findAll();
    }

    public function existeNombre(string $nombre, ?int $excluirId = null): bool
    {
        $builder = $this->where('Nombre', $nombre);

        if ($excluirId !== null) {
            $builder->where('IdDocumento !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
