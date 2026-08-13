<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Usuarios.php, Usuarios_Agregar.php, Usuarios_Consultar.php,
 * Usuarios_Modificar.php, Usuarios_ModificarPassw.php, Usuarios_Eliminar.php
 * y select_dependientesUsuarios.js.
 */
class Usuarios extends BaseController
{
    private const LARGOS_TELEFONO = [7, 10];
    private const LARGOS_CELULAR  = [10, 13];

    protected UsuarioModel $usuarios;

    public function __construct()
    {
        $this->usuarios = new UsuarioModel();
    }

    public function index()
    {
        return view('usuarios/index', [
            'titulo'   => 'Usuarios',
            'usuarios' => $this->usuarios->activos(),
        ]);
    }

    public function filtro()
    {
        $status = $this->request->getGet('opcion') === '0' ? '0' : '1';

        return view('usuarios/_tabla', [
            'usuarios' => $this->usuarios->porEstado($status),
        ]);
    }

    public function nuevo()
    {
        return view('usuarios/nuevo', ['titulo' => 'Agregar Usuarios']);
    }

    public function guardar()
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('usuarios'));
        }

        $datos = $this->datosFormulario();
        $error = $this->validar($datos, null, true);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $this->usuarios->insert([
            'Nombre'   => $datos['nombre'],
            'Telefono' => $datos['tel'],
            'Celular'  => $datos['cel'],
            'Email'    => $datos['email'],
            'Login'    => $datos['login'],
            'Password' => md5((string) $this->request->getPost('password')),
            'Tipo'     => $datos['tipo'],
            'Status'   => '1',
        ]);

        return redirect()->to(site_url('usuarios'))->with('mensaje', 'Datos guardados correctamente');
    }

    public function mostrar(int $id)
    {
        $usuario = $this->usuarios->find($id);
        if ($usuario === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('usuarios/mostrar', ['titulo' => 'Consultar Usuarios', 'usuario' => $usuario]);
    }

    public function editar(int $id)
    {
        $usuario = $this->usuarios->find($id);
        if ($usuario === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('usuarios/editar', ['titulo' => 'Modificar Usuarios', 'usuario' => $usuario]);
    }

    public function actualizar(int $id)
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('usuarios'));
        }

        $datos = $this->datosFormulario();
        $error = $this->validar($datos, $id, false);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $datosActualizar = [
            'Nombre'   => $datos['nombre'],
            'Telefono' => $datos['tel'],
            'Celular'  => $datos['cel'],
            'Email'    => $datos['email'],
            'Login'    => $datos['login'],
            'Tipo'     => $datos['tipo'],
            'Status'   => '1',
        ];

        $nuevaPassword = trim((string) $this->request->getPost('password'));
        if ($nuevaPassword !== '') {
            $datosActualizar['Password'] = md5($nuevaPassword);
        }

        $this->usuarios->update($id, $datosActualizar);

        return redirect()->to(site_url('usuarios'))->with('mensaje', 'Datos actualizados correctamente');
    }

    public function confirmarEliminar(int $id)
    {
        $usuario = $this->usuarios->find($id);
        if ($usuario === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('usuarios/eliminar', ['titulo' => 'Eliminar Usuarios', 'usuario' => $usuario]);
    }

    public function eliminar(int $id)
    {
        if ($this->request->getPost('Si')) {
            $this->usuarios->update($id, ['Status' => '0']);

            return redirect()->to(site_url('usuarios'))->with('mensaje', 'Datos eliminados correctamente');
        }

        return redirect()->to(site_url('usuarios'));
    }

    private function datosFormulario(): array
    {
        return [
            'nombre' => strtoupper(trim((string) $this->request->getPost('nombre'))),
            'tel'    => trim((string) $this->request->getPost('tel')),
            'cel'    => trim((string) $this->request->getPost('cel')),
            'email'  => trim((string) $this->request->getPost('email')),
            'login'  => strtoupper(trim((string) $this->request->getPost('login'))),
            'tipo'   => $this->request->getPost('tipo'),
        ];
    }

    private function validar(array $d, ?int $idActual, bool $requierePassword): ?string
    {
        if ($d['nombre'] === '') {
            return 'Ingrese Nombre';
        }
        if ($this->usuarios->existeNombre($d['nombre'], $idActual)) {
            return 'Este Usuario ya existe';
        }
        if ($d['tel'] !== '') {
            if (! ctype_digit($d['tel'])) {
                return 'Ingrese solo Digitos en Teléfono';
            }
            if (! in_array(strlen($d['tel']), self::LARGOS_TELEFONO, true)) {
                return 'Teléfono consta de 7 o 10 digitos';
            }
        }
        if ($d['cel'] !== '') {
            if (! ctype_digit($d['cel'])) {
                return 'Ingrese solo Digitos en Celular';
            }
            if (! in_array(strlen($d['cel']), self::LARGOS_CELULAR, true)) {
                return 'Celular consta de 10 o 13 digitos';
            }
        }
        if ($d['login'] === '') {
            return 'Ingrese Login';
        }
        if ($d['email'] !== '' && ! filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
            return 'E-Mail Incorrecto';
        }
        if ($this->usuarios->existeLogin($d['login'], $idActual)) {
            return 'Ingrese un Login diferente';
        }
        if ($requierePassword && trim((string) $this->request->getPost('password')) === '') {
            return 'Ingrese Password';
        }
        if ($d['tipo'] === null || $d['tipo'] === '-1') {
            return 'Ingrese Tipo';
        }

        return null;
    }
}
