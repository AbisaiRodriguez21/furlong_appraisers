<?php

namespace App\Models;

use CodeIgniter\Model;

class SemanaModel extends Model
{
    protected $table            = 'semanas';
    protected $primaryKey       = 'IdSem';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $allowedFields = ['No', 'FechaIni', 'FechaFin'];

    public function listar(): array
    {
        return $this->orderBy('FechaIni', 'DESC')->findAll();
    }

    /**
     * Genera el siguiente bloque de 7 días a partir de la última semana
     * registrada, igual que el botón "Generar Semana" del original.
     */
    public function generarSiguiente(): array
    {
        $ultima = $this->orderBy('IdSem', 'DESC')->first();
        $numero = ($ultima['No'] ?? 0) + 1;

        $inicio = $ultima
            ? (new \DateTimeImmutable($ultima['FechaFin']))->modify('+1 day')
            : new \DateTimeImmutable('monday this week');
        $fin = $inicio->modify('+6 days');

        $datos = [
            'No'       => $numero,
            'FechaIni' => $inicio->format('Y-m-d'),
            'FechaFin' => $fin->format('Y-m-d'),
        ];
        $this->insert($datos);

        return $datos;
    }
}
