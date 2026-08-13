<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'IdCliente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'Nombre', 'Telefono', 'Celular', 'Email', 'NombreClave',
        'Comentario', 'Direccion', 'RFC', 'Status',
    ];

    // Legacy rule (same as the original client-side JS): teléfono debe tener
    // 7 o 10 dígitos, celular 10 o 13, RFC 10, 12 o 13 caracteres. CI4 no
    // trae una regla de "una de varias longitudes exactas", así que esto se
    // valida a mano en el controlador con estas listas.
    public const LARGOS_TELEFONO = [7, 10];
    public const LARGOS_CELULAR  = [10, 13];
    public const LARGOS_RFC      = [10, 12, 13];

    protected $validationRules = [
        'Nombre'      => 'required|max_length[70]',
        'NombreClave' => 'required|max_length[20]',
        'Telefono'    => 'permit_empty|numeric',
        'Celular'     => 'permit_empty|numeric',
        'Email'       => 'permit_empty|valid_email|max_length[40]',
        'RFC'         => 'required',
        'Direccion'   => 'permit_empty|max_length[100]',
    ];

    public function activos(?string $busqueda = null): array
    {
        $builder = $this->where('Status', '1');

        if ($busqueda !== null && $busqueda !== '') {
            $builder->like('Nombre', $busqueda, 'after');
        }

        return $builder->orderBy('Nombre', 'ASC')->findAll();
    }

    public function porEstado(string $status): array
    {
        return $this->where('Status', $status)->orderBy('Nombre', 'ASC')->findAll();
    }

    public function existeDuplicado(string $nombre, string $nombreClave, string $rfc, ?int $excluirId = null): bool
    {
        $builder = $this->groupStart()
            ->where('Nombre', $nombre)
            ->orWhere('NombreClave', $nombreClave)
            ->orWhere('RFC', $rfc)
            ->groupEnd();

        if ($excluirId !== null) {
            $builder->where('IdCliente !=', $excluirId);
        }

        return $builder->countAllResults() > 0;
    }
}
