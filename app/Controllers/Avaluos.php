<?php

namespace App\Controllers;

use App\Models\AvaluoDocModel;
use App\Models\AvaluoModel;
use App\Models\ClienteModel;
use App\Models\TrabajoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Avaluos.php, AgregarAvaluo.php, ConsultarAvaluo.php,
 * ModificarAvaluo.php, ver.php, select_dependientesAvaluos.js y
 * select_dependientes_procesoAvaluos.php.
 */
class Avaluos extends BaseController
{
    public const ESTADOS = [
        'AGUASCALIENTES', 'BAJA CALIFORNIA', 'BAJA CALIFORNIA SUR', 'CAMPECHE', 'CHIAPAS',
        'CHIHUAHUA', 'COAHUILA', 'COLIMA', 'DISTRITO FEDERAL', 'DURANGO', 'EDO. MÉXICO',
        'GUANAJUATO', 'GUERRERO', 'HIDALGO', 'JALISCO', 'MICHOACÁN', 'MORELOS', 'NAYARIT',
        'NUEVO LEÓN', 'OAXACA', 'PUEBLA', 'QUERÉTARO', 'QUINTANA ROO', 'SAN LUIS POTOSÍ',
        'SINALOA', 'SONORA', 'TABASCO', 'TAMAULIPAS', 'TLAXCALA', 'VERACRUZ', 'YUCATÁN', 'ZACATECAS',
    ];

    protected AvaluoModel $avaluos;
    protected AvaluoDocModel $avaluoDocs;
    protected TrabajoModel $trabajos;
    protected ClienteModel $clientes;
    protected \App\Models\DocTrabajoModel $docTrabajos;

    public function __construct()
    {
        $this->avaluos     = new AvaluoModel();
        $this->avaluoDocs  = new AvaluoDocModel();
        $this->trabajos    = new TrabajoModel();
        $this->clientes    = new ClienteModel();
        $this->docTrabajos = new \App\Models\DocTrabajoModel();
    }

    public function index()
    {
        $ano = (int) ($this->request->getGet('ano') ?: date('Y'));

        return view('avaluos/index', [
            'titulo'   => 'Avalúos',
            'ano'      => $ano,
            'trabajos' => $this->trabajos->activos(),
            'avaluos'  => $this->avaluos->listar($ano),
            'estatus'  => AvaluoModel::ESTATUS,
        ]);
    }

    /**
     * AJAX: recarga la tabla al cambiar año / tipo de trabajo / estatus
     * (reemplaza select_dependientes_procesoAvaluos.php).
     */
    public function filtro()
    {
        $ano      = (int) $this->request->getGet('ano');
        $idTrabajo = (int) $this->request->getGet('trabajo');
        $status   = (int) $this->request->getGet('status');

        return view('avaluos/_tabla', [
            'avaluos' => $this->avaluos->listar($ano, $idTrabajo, $status),
            'estatus' => AvaluoModel::ESTATUS,
        ]);
    }

    public function cancelar(string $folio)
    {
        $this->avaluos->cancelar($folio);

        return $this->response->setJSON(['ok' => true]);
    }

    public function imagen(string $folio)
    {
        $avaluo = $this->avaluos->find($folio);

        if ($avaluo === null || $avaluo['Img'] === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response->setContentType('image/jpeg')->setBody($avaluo['Img']);
    }

    public function nuevo()
    {
        return view('avaluos/nuevo', [
            'titulo'   => 'Agregar Avalúo',
            'trabajos' => $this->trabajos->activos(),
            'estados'  => self::ESTADOS,
        ]);
    }

    /**
     * AJAX: checklist de documentos que exige el trabajo elegido
     * (reemplaza select_dependientes_proceso.php con tipo=0).
     */
    public function documentosDeTrabajo(int $idTrabajo)
    {
        $documentos = $this->docTrabajos->documentosDe($idTrabajo);

        return view('avaluos/_checklist_nuevo', ['documentos' => $documentos]);
    }

    public function guardar()
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('avaluos'));
        }

        $post  = $this->request->getPost();
        $error = $this->validarAlta($post);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $imagen = $this->leerImagenSubida();

        $idsRecibidosAlCrear = array_map('intval', array_keys((array) $this->request->getPost('documentos')));

        $numero = max(1, (int) $post['numeroAvaluos']);
        $anoSolicitud = (int) substr($post['fechaSolicitud'], 0, 4);
        $usuario = (int) session('id_usuario');

        for ($n = 0; $n < $numero; $n++) {
            $folio = $this->avaluos->siguienteFolio($anoSolicitud);

            $this->avaluos->insert([
                'Folio'            => $folio,
                'FechaSolicitud'   => $post['fechaSolicitud'],
                'NombreInspeccion' => strtoupper(trim((string) $post['nombreInspector'])),
                'FechaInspeccion'  => $post['fechaInspeccion'],
                'FechaVencimiento' => $post['fechaVencimiento'],
                'FechaEntregado'   => null,
                'IdTrabajo'        => $post['trabajo'],
                'IdCliente'        => $post['cliente'],
                'Solicitante'      => $post['solicitante'],
                'Propietario'      => $post['propietario'],
                'Direccion'        => strtoupper(trim((string) $post['direccion'])),
                'Colonia'          => strtoupper(trim((string) $post['colonia'])),
                'Ciudad'           => strtoupper(trim((string) $post['ciudad'])),
                'Estado'           => $post['estado'],
                'NombreArchivo'    => strtoupper(trim((string) $post['nombreArchivo'])),
                'ValorAvaluo'      => $post['valorAvaluo'],
                'ValorReal'        => $post['valorReal'],
                'Honorarios'       => $post['honorarios'],
                'Img'              => $imagen ?? '',
                'Status'           => '5',
                'Comentario'       => strtoupper(trim((string) $post['comentario'])),
                'Tipo'             => $post['tipo'],
                'FechaTerminacion' => null,
            ]);

            $this->avaluoDocs->crearChecklist($folio, (int) $post['trabajo'], $idsRecibidosAlCrear, $usuario);
        }

        return redirect()->to(site_url('avaluos'))->with('mensaje', 'Datos guardados correctamente');
    }

    public function mostrar(string $folio)
    {
        $avaluo = $this->avaluos->conNombres($folio);

        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('avaluos/mostrar', [
            'titulo'    => 'Consultar Avalúo',
            'avaluo'    => $avaluo,
            'checklist' => $this->avaluoDocs->checklistCompleto($folio),
        ]);
    }

    public function editar(string $folio)
    {
        $avaluo = $this->avaluos->conNombres($folio);

        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('avaluos/editar', [
            'titulo'     => 'Modificar Avalúo',
            'avaluo'     => $avaluo,
            'trabajos'   => $this->trabajos->activos(),
            'estados'    => self::ESTADOS,
            'pendientes' => $this->avaluoDocs->pendientes($folio, (int) $avaluo['IdTrabajo']),
            'recibidos'  => $this->avaluoDocs->checklistCompleto($folio),
            'opcionesStatus' => $this->opcionesStatus((int) $avaluo['Status']),
        ]);
    }

    public function actualizar(string $folio)
    {
        if ($this->request->getPost('cancelar')) {
            return redirect()->to(site_url('avaluos'));
        }

        $avaluo = $this->avaluos->find($folio);
        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $post  = $this->request->getPost();
        $error = $this->validarEdicion($post);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $usuario = (int) session('id_usuario');
        $idTrabajoNuevo = (int) $post['trabajo'];
        $cambioTrabajo  = $idTrabajoNuevo !== (int) $avaluo['IdTrabajo'];

        if ($cambioTrabajo) {
            $this->avaluoDocs->reemplazarPorCambioDeTrabajo($folio, $idTrabajoNuevo, $usuario);
        }

        // Documentos marcados como recibidos ahora, entre los que seguían pendientes.
        $pendientesAntes = $this->avaluoDocs->pendientes($folio, $idTrabajoNuevo);
        $marcadosAhora   = array_map('intval', array_keys((array) $this->request->getPost('documentos')));
        $this->avaluoDocs->marcarRecibidos($folio, $marcadosAhora, $usuario);

        $status = $this->derivarStatus($post, (int) $avaluo['Status'], $folio, $idTrabajoNuevo);

        $datos = [
            'FechaSolicitud'   => $post['fechaSolicitud'],
            'NombreInspeccion' => strtoupper(trim((string) $post['nombreInspector'])),
            'FechaInspeccion'  => $post['fechaInspeccion'],
            'FechaVencimiento' => $post['fechaVencimiento'],
            'FechaEntregado'   => $post['fechaEntrega'] !== '' ? $post['fechaEntrega'] : null,
            'FechaTerminacion' => $post['fechaTer'] !== '' ? $post['fechaTer'] : null,
            'IdTrabajo'        => $idTrabajoNuevo,
            'IdCliente'        => $post['cliente'],
            'Solicitante'      => $post['solicitante'],
            'Propietario'      => $post['propietario'],
            'Direccion'        => strtoupper(trim((string) $post['direccion'])),
            'Colonia'          => strtoupper(trim((string) $post['colonia'])),
            'Ciudad'           => strtoupper(trim((string) $post['ciudad'])),
            'Estado'           => $post['estado'],
            'NombreArchivo'    => strtoupper(trim((string) $post['nombreArchivo'])),
            'ValorAvaluo'      => $post['valorAvaluo'],
            'ValorReal'        => $post['valorReal'],
            'Honorarios'       => $post['honorarios'],
            'Status'           => $status,
            'Comentario'       => strtoupper(trim((string) $post['comentario'])),
            'Tipo'             => $post['tipo'],
        ];

        $imagen = $this->leerImagenSubida();
        if ($imagen !== null) {
            $datos['Img'] = $imagen;
        }

        $this->avaluos->update($folio, $datos);

        return redirect()->to(site_url('avaluos'))->with('mensaje', 'Datos actualizados correctamente');
    }

    // ---------------------------------------------------------------

    private function leerImagenSubida(): ?string
    {
        $archivo = $this->request->getFile('archivo');

        if ($archivo === null || ! $archivo->isValid() || $archivo->getSize() === 0) {
            return null;
        }

        return file_get_contents($archivo->getTempName());
    }

    private function validarAlta(array $post): ?string
    {
        foreach (['cliente' => 'Cliente', 'solicitante' => 'Solicitante', 'propietario' => 'Propietario'] as $campo => $etiqueta) {
            if ((int) ($post[$campo] ?? 0) === 0) {
                return 'Seleccione ' . $etiqueta;
            }
        }
        foreach (['direccion' => 'Direccion', 'colonia' => 'Colonia', 'ciudad' => 'Ciudad'] as $campo => $etiqueta) {
            if (trim((string) ($post[$campo] ?? '')) === '') {
                return 'Ingrese ' . $etiqueta;
            }
        }
        if ((string) ($post['estado'] ?? '0') === '0') {
            return 'Seleccione Estado';
        }
        if (! isset($post['tipo']) || $post['tipo'] === '-1') {
            return 'Seleccione Tipo';
        }
        if ((int) ($post['trabajo'] ?? 0) === 0) {
            return 'Seleccione Trabajo';
        }

        return $this->validarMontos($post);
    }

    private function validarEdicion(array $post): ?string
    {
        foreach (['direccion' => 'Direccion', 'colonia' => 'Colonia', 'ciudad' => 'Ciudad'] as $campo => $etiqueta) {
            if (trim((string) ($post[$campo] ?? '')) === '') {
                return 'Ingrese ' . $etiqueta;
            }
        }

        return $this->validarMontos($post);
    }

    private function validarMontos(array $post): ?string
    {
        $patronGrande  = '/^\d{0,9}(\.\d{0,2})?$/';
        $patronHonor   = '/^\d{0,7}(\.\d{0,2})?$/';

        if (! preg_match($patronGrande, (string) $post['valorAvaluo'])) {
            return 'Valor muy grande para Valor de Avaluo';
        }
        if (! preg_match($patronGrande, (string) $post['valorReal'])) {
            return 'Valor muy grande para Valor Real';
        }
        if (! preg_match($patronHonor, (string) $post['honorarios'])) {
            return 'Valor muy grande para Honorarios';
        }

        return null;
    }

    /**
     * Igual que el <select> de estatus del original: qué opciones se
     * muestran depende del estatus actual del avalúo.
     */
    private function opcionesStatus(int $actual): array
    {
        return match (true) {
            in_array($actual, [1, 2, 5, 3], true) => ['0' => 'SELECCIONAR STATUS', '7' => 'PENDIENTE', '5' => 'EN PROCESO'],
            $actual === 7 => ['0' => 'SELECCIONAR STATUS', '5' => 'EN PROCESO'],
            default => ['0' => 'SELECCIONAR STATUS', '7' => 'PENDIENTE', '5' => 'EN PROCESO'],
        };
    }

    /**
     * Reproduce la máquina de estatus de ModificarAvaluo.php: si el usuario
     * elige explícitamente Pendiente/En Proceso se respeta; si no, el
     * estatus se calcula a partir de las fechas de entrega/terminación y de
     * si ya venció.
     */
    private function derivarStatus(array $post, int $statusActual, string $folio, int $idTrabajo): string
    {
        $statusElegido = isset($post['status']) && $post['status'] !== '' ? (int) $post['status'] : $statusActual;

        if ($statusElegido !== 0 && $statusElegido !== 6) {
            return (string) $statusElegido;
        }

        $fechaEntrega = $post['fechaEntrega'] ?? '';
        $fechaTer     = $post['fechaTer'] ?? '';

        if ($fechaTer !== '' && $fechaEntrega === '') {
            return '3'; // Terminado
        }

        if ($fechaEntrega !== '') {
            return '6'; // Entregado
        }

        if (date('Y-m-d') > (string) $post['fechaVencimiento']) {
            return '2'; // Vencido
        }

        return '5'; // En Proceso
    }
}
