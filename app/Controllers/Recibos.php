<?php

namespace App\Controllers;

use App\Models\ReciboModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Recibos.php, ReciboHonorarios.php, ReciboHonorariosCambiar.php,
 * ReciboHonorariosCancelar.php, ReciboHonorariosConsulta.php y
 * ReciboHonorarios_Proceso.php.
 */
class Recibos extends BaseController
{
    protected ReciboModel $recibos;

    public function __construct()
    {
        $this->recibos = new ReciboModel();
    }

    public function index()
    {
        return view('recibos/index', [
            'titulo'  => 'Recibos de Honorarios',
            'recibos' => $this->recibos->listar(),
        ]);
    }

    public function nuevo()
    {
        $folio = $this->recibos->siguienteFolio();

        return view('recibos/nuevo', [
            'titulo' => 'Recibo de Honorarios',
            'folio'  => $folio,
            'permitido' => $folio <= ReciboModel::FOLIO_MAXIMO,
        ]);
    }

    public function guardar()
    {
        if ((int) $this->request->getPost('opc') !== 1) {
            return redirect()->to(site_url('recibos'));
        }

        $post  = $this->request->getPost();
        $folio = (int) $post['noFolio'];

        if ($folio > ReciboModel::FOLIO_MAXIMO) {
            return redirect()->to(site_url('recibos'));
        }

        $metodoPago = (int) $post['metodoPago'];

        $this->recibos->insert([
            'NoFolio'    => $folio,
            'Fecha'      => $post['fecha'],
            'IdCliente'  => $post['numcli'],
            'Concepto'   => strtoupper((string) $post['concepto']),
            'Cantidad'   => $this->numero($post['cantidad']),
            'Honorarios' => $this->numero($post['hono']),
            'Iva'        => $this->numero($post['iva']),
            'Subtotal'   => $this->numero($post['subtotal']),
            'RetISR'     => $this->numero($post['retISR'] ?? '0'),
            'RetIVA'     => $this->numero($post['retIVA'] ?? '0'),
            'Status'     => '0',
            'MotivoC'    => '',
            'MetodoPago' => $metodoPago,
            'CtaBanco'   => $metodoPago === 3 ? (string) $post['cuenta'] : '',
        ]);

        return view('recibos/impreso', [
            'titulo' => 'Recibo de Honorarios',
            'recibo' => $this->recibos->conCliente($folio),
            'letra'  => (string) $post['letra'],
        ]);
    }

    public function consultar(int $folio)
    {
        $recibo = $this->recibos->conCliente($folio);
        if ($recibo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('recibos/consultar', ['titulo' => 'Consultar Recibo', 'recibo' => $recibo]);
    }

    public function editar(int $folio)
    {
        $recibo = $this->recibos->conCliente($folio);
        if ($recibo === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        if ((int) $recibo['Status'] === 1) {
            return redirect()->to(site_url('recibos'))->with('error', 'No se Puede Modificar un recibo Cancelado');
        }

        return view('recibos/editar', ['titulo' => 'Modificar Recibo', 'recibo' => $recibo]);
    }

    public function actualizar(int $folio)
    {
        if ((int) $this->request->getPost('opc') !== 1) {
            return redirect()->to(site_url('recibos'));
        }

        $recibo = $this->recibos->find($folio);
        if ($recibo === null || (int) $recibo['Status'] === 1) {
            return redirect()->to(site_url('recibos'));
        }

        $post = $this->request->getPost();
        $metodoPago = (int) $post['metodoPago'];

        $this->recibos->update($folio, [
            'Fecha'      => $post['fecha'],
            'IdCliente'  => $post['numcli'],
            'Concepto'   => strtoupper((string) $post['concepto']),
            'Cantidad'   => $this->numero($post['cantidad']),
            'Honorarios' => $this->numero($post['hono']),
            'Iva'        => $this->numero($post['iva']),
            'Subtotal'   => $this->numero($post['subtotal']),
            'RetISR'     => $this->numero($post['retISR'] ?? '0'),
            'RetIVA'     => $this->numero($post['retIVA'] ?? '0'),
            'MetodoPago' => $metodoPago,
            'CtaBanco'   => $metodoPago === 3 ? (string) $post['cuenta'] : '',
        ]);

        return view('recibos/impreso', [
            'titulo' => 'Recibo de Honorarios',
            'recibo' => $this->recibos->conCliente($folio),
            'letra'  => (string) $post['letra'],
        ]);
    }

    public function confirmarCancelar(int $folio)
    {
        $recibo = $this->recibos->conCliente($folio);
        if ($recibo === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        if ((int) $recibo['Status'] === 1) {
            return redirect()->to(site_url('recibos'))->with('error', 'Este recibo ya ha sido Cancelado');
        }

        return view('recibos/cancelar', ['titulo' => 'Cancelar Recibo de Honorarios', 'recibo' => $recibo]);
    }

    public function cancelar(int $folio)
    {
        if ((int) $this->request->getPost('opc') !== 1) {
            return redirect()->to(site_url('recibos'));
        }

        $motivo = trim((string) $this->request->getPost('motivoC'));
        if ($motivo === '') {
            return redirect()->back()->with('error', 'Ingrese motivo de cancelación');
        }

        $this->recibos->cancelar($folio, strtoupper($motivo));

        return redirect()->to(site_url('recibos'))->with('mensaje', 'Se ha Cancelado el Recibo');
    }

    private function numero(string $valor): float
    {
        return (float) str_replace(',', '', $valor);
    }
}
