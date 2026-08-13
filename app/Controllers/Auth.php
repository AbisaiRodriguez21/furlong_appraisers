<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

/**
 * Replaces index.php (login form) + control.php (credential check) +
 * salir.php (logout) from the legacy system.
 */
class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('autenticado')) {
            return redirect()->to('/');
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $login    = strtoupper(trim((string) $this->request->getPost('login')));
        $password = (string) $this->request->getPost('password');

        $usuarios = new UsuarioModel();
        $usuario  = $usuarios->findActiveByLogin($login);

        // Same hashing scheme as the legacy system (unsalted MD5), kept as-is
        // for this phase so existing accounts keep working unchanged.
        if ($usuario === null || $usuario['Password'] !== md5($password)) {
            return redirect()->to('/login?error=1');
        }

        session()->regenerate();
        session()->set([
            'autenticado' => true,
            'id_usuario'  => $usuario['IdUsuario'],
            'login'       => $usuario['Login'],
            'nombre'      => $usuario['Nombre'],
            'tipo'        => $usuario['Tipo'],
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
