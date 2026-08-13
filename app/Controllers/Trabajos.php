<?php

namespace App\Controllers;

use App\Models\DocTrabajoModel;
use App\Models\DocumentoModel;
use App\Models\TrabajoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Trabajos.php, AgregarTrabajo.php, ConsultarTrabajo.php,
 * ModificarTrabajo.php, EliminarTrabajo.php y select_dependientesTrabajos.js.
 */
class Trabajos extends BaseController
{
    protected TrabajoModel $trabajos;
    protected DocumentoModel $documentos;
    protected DocTrabajoModel $docTrabajos;

    public function __construct()
    {
        $this->trabajos    = new TrabajoModel();
        $this->documentos  = new DocumentoModel();
        $this->docTrabajos = new DocTrabajoModel();
    }

    public function index()
    {
        return view('trabajos/index', [
            'titulo'   => 'Trabajos',
            'trabajos' => $this->trabajos->activos(),
        ]);
    }

    /**
     * AJAX partial para el filtro Activos/Inactivos.
     */
    public function filtro()
    {
        $status = $this->request->getGet('opcion') === '0' ? '0' : '1';

        return view('trabajos/_tabla', [
            'trabajos' => $this->trabajos->porEstado($status),
        ]);
    }

    public function nuevo()
    {
        return view('trabajos/nuevo', [
            'titulo'     => 'Agregar Trabajo',
            'documentos' => $this->documentos->activos(),
        ]);
    }

    public function guardar()
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('trabajos'));
        }

        $nombre      = strtoupper(trim((string) $this->request->getPost('nombre')));
        $descripcion = strtoupper(trim((string) $this->request->getPost('descripcion')));
        $documentos  = $this->documentosSeleccionados();

        $error = $this->validar($nombre, $documentos, null);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $idTrabajo = $this->trabajos->insert([
            'Nombre'      => $nombre,
            'Descripcion' => $descripcion,
            'Status'      => '1',
        ]);

        $this->docTrabajos->reemplazarDocumentos($idTrabajo, $documentos);

        return redirect()->to(site_url('trabajos'))->with('mensaje', 'Datos guardados correctamente');
    }

    public function mostrar(int $id)
    {
        $trabajo = $this->trabajos->find($id);

        if ($trabajo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('trabajos/mostrar', [
            'titulo'            => 'Consultar Trabajo',
            'trabajo'           => $trabajo,
            'documentosActuales' => $this->docTrabajos->documentosDe($id),
        ]);
    }

    public function editar(int $id)
    {
        $trabajo = $this->trabajos->find($id);

        if ($trabajo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $actuales = $this->docTrabajos->documentosDe($id);
        $idsActuales = array_column($actuales, 'IdDocumento');

        $disponibles = array_filter(
            $this->documentos->activos(),
            static fn (array $doc): bool => ! in_array($doc['IdDocumento'], $idsActuales, true)
        );

        return view('trabajos/editar', [
            'titulo'             => 'Modificar Trabajo',
            'trabajo'            => $trabajo,
            'documentosActuales' => $actuales,
            'documentosNuevos'   => $disponibles,
        ]);
    }

    public function actualizar(int $id)
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('trabajos'));
        }

        $nombre      = strtoupper(trim((string) $this->request->getPost('nombre')));
        $descripcion = strtoupper(trim((string) $this->request->getPost('descripcion')));
        $documentos  = $this->documentosSeleccionados();

        $error = $this->validar($nombre, $documentos, $id);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $this->trabajos->update($id, [
            'Nombre'      => $nombre,
            'Descripcion' => $descripcion,
        ]);

        $this->docTrabajos->reemplazarDocumentos($id, $documentos);

        return redirect()->to(site_url('trabajos'))->with('mensaje', 'Datos actualizados correctamente');
    }

    public function confirmarEliminar(int $id)
    {
        $trabajo = $this->trabajos->find($id);

        if ($trabajo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('trabajos/eliminar', [
            'titulo'            => 'Eliminar Trabajo',
            'trabajo'           => $trabajo,
            'documentosActuales' => $this->docTrabajos->documentosDe($id),
        ]);
    }

    public function eliminar(int $id)
    {
        if ($this->request->getPost('Eliminar')) {
            $this->trabajos->update($id, ['Status' => '0']);

            return redirect()->to(site_url('trabajos'))->with('mensaje', 'Datos eliminados correctamente');
        }

        return redirect()->to(site_url('trabajos'));
    }

    /**
     * Lee los checkboxes "documentos[IdDocumento]" / "oblig[IdDocumento]"
     * enviados desde cualquiera de las listas del formulario (actuales o
     * nuevos en la edición; la única lista en el alta).
     *
     * @return list<array{IdDocumento:int,Tipo:int}>
     */
    private function documentosSeleccionados(): array
    {
        $marcados   = (array) $this->request->getPost('documentos');
        $obligatorios = (array) $this->request->getPost('oblig');

        $seleccion = [];
        foreach (array_keys($marcados) as $idDocumento) {
            $seleccion[] = [
                'IdDocumento' => (int) $idDocumento,
                'Tipo'        => isset($obligatorios[$idDocumento]) ? 1 : 0,
            ];
        }

        return $seleccion;
    }

    private function validar(string $nombre, array $documentos, ?int $idActual): ?string
    {
        if ($nombre === '') {
            return 'Ingrese Nombre';
        }

        if ($documentos === []) {
            return 'Seleccione al menos un documento';
        }

        if ($this->trabajos->existeNombre($nombre, $idActual)) {
            return 'Este trabajo ya existe';
        }

        return null;
    }
}
