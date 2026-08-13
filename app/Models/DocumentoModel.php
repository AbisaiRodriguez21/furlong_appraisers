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
}
