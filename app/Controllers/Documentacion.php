<?php

namespace App\Controllers;

use App\Models\DocumentoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Documentacion.php, AgregarDocumentacion.php,
 * ModificarDocumentacion.php, EliminarDocumentacion.php
 * y select_dependientesDocumentacion.php.
 */
class Documentacion extends BaseController
{
    protected DocumentoModel $documentos;

    public function __construct()
    {
        $this->documentos = new DocumentoModel();
    }

    public function index()
    {
        return view('documentacion/index', [
            'titulo'     => 'Documentación',
            'documentos' => $this->documentos->activos(),
        ]);
    }

    /**
     * AJAX partial para el filtro Activos/Inactivos.
     */
    public function filtro()
    {
        $status = $this->request->getGet('opcion') === '0' ? '0' : '1';

        return view('documentacion/_tabla', [
            'documentos' => $this->documentos->porEstado($status),
        ]);
    }

    public function nuevo()
    {
        return view('documentacion/nuevo', ['titulo' => 'Agregar Documentación']);
    }

    public function guardar()
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('documentacion'));
        }

        $nombres = array_filter(array_map(
            static fn ($n) => strtoupper(trim((string) $n)),
            (array) $this->request->getPost('documento')
        ));

        if ($nombres === []) {
            return redirect()->back()->withInput()->with('error', 'Ingrese al menos un Documento');
        }

        $duplicados = [];
        foreach ($nombres as $nombre) {
            if ($this->documentos->existeNombre($nombre)) {
                $duplicados[] = $nombre;
                continue;
            }
            $this->documentos->insert(['Nombre' => $nombre, 'Status' => '1']);
        }

        $mensaje = 'Datos guardados correctamente';
        if ($duplicados !== []) {
            $mensaje .= '. Ya existían y no se repitieron: ' . implode(', ', $duplicados);
        }

        return redirect()->to(site_url('documentacion'))->with('mensaje', $mensaje);
    }

    public function editar(int $id)
    {
        $documento = $this->documentos->find($id);

        if ($documento === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('documentacion/editar', [
            'titulo'    => 'Modificar Documentación',
            'documento' => $documento,
        ]);
    }

    public function actualizar(int $id)
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('documentacion'));
        }

        $nombre = strtoupper(trim((string) $this->request->getPost('nombre')));

        if ($nombre === '') {
            return redirect()->back()->withInput()->with('error', 'Ingrese Nombre');
        }

        if ($this->documentos->existeNombre($nombre, $id)) {
            return redirect()->back()->withInput()->with('error', 'Este Documento ya existe');
        }

        $this->documentos->update($id, ['Nombre' => $nombre]);

        return redirect()->to(site_url('documentacion'))->with('mensaje', 'Datos actualizados correctamente');
    }

    public function confirmarEliminar(int $id)
    {
        $documento = $this->documentos->find($id);

        if ($documento === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('documentacion/eliminar', [
            'titulo'    => 'Eliminar Documentación',
            'documento' => $documento,
        ]);
    }

    public function eliminar(int $id)
    {
        if ($this->request->getPost('button')) {
            $this->documentos->update($id, ['Status' => '0']);

            return redirect()->to(site_url('documentacion'))->with('mensaje', 'Datos eliminados correctamente');
        }

        return redirect()->to(site_url('documentacion'));
    }
}
