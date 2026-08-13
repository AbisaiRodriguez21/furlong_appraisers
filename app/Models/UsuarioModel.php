<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'IdUsuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'Nombre', 'Telefono', 'Celular', 'Email', 'Login', 'Password', 'Tipo', 'Status',
    ];

    public function findActiveByLogin(string $login): ?array
    {
        return $this->where('Login', $login)
            ->where('Status', '1')
            ->first();
    }

    public function activos(): array
    {
        return $this->where('Status', '1')->orderBy('IdUsuario', 'ASC')->findAll();
    }

    public function porEstado(string $status): array
    {
        return $this->where('Status', $status)->orderBy('IdUsuario', 'ASC')->findAll();
    }

    public function existeNombre(string $nombre, ?int $excluirId = null): bool
    {
        $builder = $this->where('Nombre', $nombre);
        if ($excluirId !== null) {
            $builder->where('IdUsuario !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }

    public function existeLogin(string $login, ?int $excluirId = null): bool
    {
        $builder = $this->where('Login', $login);
        if ($excluirId !== null) {
            $builder->where('IdUsuario !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
