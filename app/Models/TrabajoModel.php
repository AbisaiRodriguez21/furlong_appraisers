<?php

namespace App\Models;

use CodeIgniter\Model;

class TrabajoModel extends Model
{
    protected $table            = 'trabajos';
    protected $primaryKey       = 'IdTrabajo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['Nombre', 'Descripcion', 'Status'];

    protected $validationRules = [
        'Nombre'      => 'required|max_length[60]',
        'Descripcion' => 'permit_empty',
    ];

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
            $builder->where('IdTrabajo !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
