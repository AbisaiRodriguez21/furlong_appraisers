<?php

namespace App\Controllers;

use App\Models\ClienteModel;

/**
 * Replaces Clientes.php, Clientes_Agregar.php, Clientes_Consultar.php,
 * Clientes_Modificar.php, Clientes_Eliminar.php, select_dependientesClientes.js
 * and select_dependientes_procesoClientes.php from the legacy system.
 */
class Clientes extends BaseController
{
    protected ClienteModel $clientes;

    public function __construct()
    {
        $this->clientes = new ClienteModel();
    }

    public function index()
    {
        return view('clientes/index', [
            'titulo'   => 'Clientes',
            'clientes' => $this->clientes->activos(),
            't'        => $this->request->getGet('t'),
            'ventana'  => $this->request->getGet('ventana'),
        ]);
    }

    /**
     * AJAX partial for the Activos/Inactivos dropdown
     * (replaces select_dependientes_procesoClientes.php).
     */
    public function filtro()
    {
        $status = $this->request->getGet('opcion') === '0' ? '0' : '1';

        return view('clientes/_tabla', [
            'clientes' => $this->clientes->porEstado($status),
        ]);
    }

    public function nuevo()
    {
        return view('clientes/nuevo', [
            'titulo' => 'Agregar Clientes',
            't'      => $this->request->getGet('t'),
        ]);
    }

    public function guardar()
    {
        $t = $this->request->getPost('t');

        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''));
        }

        $datos = $this->datosFormulario();
        $error = $this->validarDatos($datos, null);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $datos['Status'] = '1';
        $this->clientes->insert($datos);

        return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''))
            ->with('mensaje', 'Datos guardados correctamente');
    }

    public function mostrar(int $id)
    {
        $cliente = $this->clientes->find($id);

        if ($cliente === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clientes/mostrar', [
            'titulo'  => 'Consultar Clientes',
            'cliente' => $cliente,
            't'       => $this->request->getGet('t'),
        ]);
    }

    public function editar(int $id)
    {
        $cliente = $this->clientes->find($id);

        if ($cliente === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clientes/editar', [
            'titulo'  => 'Modificar Clientes',
            'cliente' => $cliente,
            't'       => $this->request->getGet('t'),
        ]);
    }

    public function actualizar(int $id)
    {
        $t = $this->request->getPost('t');

        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''));
        }

        $datos = $this->datosFormulario();
        $error = $this->validarDatos($datos, $id);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $this->clientes->update($id, $datos);

        return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''))
            ->with('mensaje', 'Datos actualizados correctamente');
    }

    public function confirmarEliminar(int $id)
    {
        $cliente = $this->clientes->find($id);

        if ($cliente === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('clientes/eliminar', [
            'titulo'  => 'Eliminar Clientes',
            'cliente' => $cliente,
            't'       => $this->request->getGet('t'),
        ]);
    }

    public function eliminar(int $id)
    {
        $t = $this->request->getPost('t');

        if ($this->request->getPost('Si')) {
            $this->clientes->update($id, ['Status' => '0']);

            return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''))
                ->with('mensaje', 'Datos eliminados correctamente');
        }

        return redirect()->to(site_url('clientes') . ($t ? '?t=' . $t : ''));
    }

    private function datosFormulario(): array
    {
        return [
            'Nombre'      => strtoupper(trim((string) $this->request->getPost('nombre'))),
            'NombreClave' => strtoupper(trim((string) $this->request->getPost('nombreC'))),
            'Telefono'    => trim((string) $this->request->getPost('tel')),
            'Celular'     => trim((string) $this->request->getPost('cel')),
            'Email'       => trim((string) $this->request->getPost('email')),
            'Direccion'   => strtoupper(trim((string) $this->request->getPost('direc'))),
            'RFC'         => strtoupper(trim((string) $this->request->getPost('rfc'))),
            'Comentario'  => strtoupper(trim((string) $this->request->getPost('comen'))),
        ];
    }

    /**
     * Same rules the legacy JS enforced before submit. Returns an error
     * message, or null when everything is valid.
     */
    private function validarDatos(array $datos, ?int $idActual): ?string
    {
        $validacion = service('validation');
        $validacion->setRules($this->clientes->getValidationRules());

        if (! $validacion->run($datos)) {
            return implode(' ', $validacion->getErrors());
        }

        if ($datos['Telefono'] !== '' && ! in_array(strlen($datos['Telefono']), ClienteModel::LARGOS_TELEFONO, true)) {
            return 'El teléfono consta de 7 o 10 dígitos.';
        }

        if ($datos['Celular'] !== '' && ! in_array(strlen($datos['Celular']), ClienteModel::LARGOS_CELULAR, true)) {
            return 'El celular consta de 10 o 13 dígitos.';
        }

        if (! in_array(strlen($datos['RFC']), ClienteModel::LARGOS_RFC, true)) {
            return 'El RFC consta de 10, 12 o 13 caracteres.';
        }

        if ($this->clientes->existeDuplicado($datos['Nombre'], $datos['NombreClave'], $datos['RFC'], $idActual)) {
            return 'Este cliente ya existe.';
        }

        return null;
    }
}
