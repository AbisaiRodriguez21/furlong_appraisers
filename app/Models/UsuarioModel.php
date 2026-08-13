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
}
