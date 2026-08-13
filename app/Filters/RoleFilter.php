<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Central replacement for the per-file "if($_SESSION['Tipo']==...)" checks
 * scattered across the legacy system. Usage in Routes.php: 'role:1,2'
 * allows Tipo 1 (Administrador) and Tipo 2 (Gerente General).
 *
 * Unlike the legacy behaviour (which bounced denied users to the login
 * screen pretending the password was wrong), this shows a real
 * "access denied" page and leaves the session intact.
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tipo = session()->get('tipo');

        if ($tipo === null) {
            return redirect()->to('/login');
        }

        $allowed = $arguments ?? [];

        if ($allowed !== [] && ! in_array((string) $tipo, $allowed, true)) {
            return service('response')
                ->setStatusCode(403)
                ->setBody(view('errors/permiso_denegado'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here.
    }
}
