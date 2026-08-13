<?php

namespace App\Controllers;

use App\Models\SemanaModel;

/**
 * Reemplaza Semanas.php: el <select> de semanas usado por los selectores
 * de reportes de Avalúos y Cobranza, y el botón "Generar Semana".
 */
class Semanas extends BaseController
{
    public function opciones()
    {
        $semanas = (new SemanaModel())->listar();

        return view('reportes/_semanas_opciones', ['semanas' => $semanas]);
    }

    public function generar()
    {
        (new SemanaModel())->generarSiguiente();

        return $this->opciones();
    }
}
