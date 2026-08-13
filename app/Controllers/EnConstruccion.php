<?php

namespace App\Controllers;

/**
 * Temporary placeholder for menu items that haven't been migrated from the
 * legacy system yet. Each one gets replaced by its real controller as that
 * module is ported.
 */
class EnConstruccion extends BaseController
{
    public function index(string $modulo = ''): string
    {
        return view('dashboard/en_construccion', [
            'titulo' => 'En construcción',
            'modulo' => $modulo,
        ]);
    }
}
