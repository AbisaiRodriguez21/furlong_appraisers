<?php

namespace App\Controllers;

use App\Models\CobranzaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Replaces Cobranza.php, CobranzaPagar.php, CobranzaVer.php,
 * CobranzaModifP.php, eliminarPago.php, select_dependientesCobranza.js y
 * select_dependientes_procesoCobranza.php.
 */
class Cobranza extends BaseController
{
    protected CobranzaModel $cobranza;

    public function __construct()
    {
        $this->cobranza = new CobranzaModel();
    }

    public function index()
    {
        $ano = (int) ($this->request->getGet('ano') ?: date('Y'));

        return view('cobranza/index', [
            'titulo'  => 'Cobranza',
            'ano'     => $ano,
            'filas'   => $this->cobranza->listar($ano, 1),
        ]);
    }

    /**
     * AJAX: recarga la tabla al cambiar año / Todos-Adeudos-Pagados
     * (reemplaza select_dependientes_procesoCobranza.php).
     */
    public function filtro()
    {
        $ano    = (int) $this->request->getGet('ano');
        $filtro = (int) $this->request->getGet('opcion');

        return view('cobranza/_tabla', [
            'filas' => $this->cobranza->listar($ano, $filtro),
        ]);
    }

    public function pagar(string $folio)
    {
        $avaluo = $this->cobranza->datosAvaluo($folio);

        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('cobranza/pagar', [
            'titulo' => 'Cobranza',
            'avaluo' => $avaluo,
            'saldo'  => $this->cobranza->saldoDe($folio, (float) $avaluo['Honorarios']),
        ]);
    }

    public function pagarGuardar(string $folio)
    {
        if ((int) $this->request->getPost('opc') !== 1) {
            return redirect()->to(site_url('cobranza'));
        }

        $avaluo = $this->cobranza->datosAvaluo($folio);
        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $pago = (float) $this->request->getPost('pago');
        $saldo = $this->cobranza->saldoDe($folio, (float) $avaluo['Honorarios']);

        if ($pago <= 0 || $pago > $saldo) {
            return redirect()->back()->with('error', $pago <= 0 ? 'Pago debe ser mayor a 0' : 'El Pago Excede el saldo por cobrar');
        }

        $this->cobranza->registrarPago($folio, $pago);

        return redirect()->to(site_url('cobranza'))->with('mensaje', 'Datos guardados correctamente');
    }

    public function ver(string $folio)
    {
        $avaluo = $this->cobranza->datosAvaluo($folio);

        if ($avaluo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('cobranza/ver', [
            'titulo' => 'Cobranza Historial',
            'avaluo' => $avaluo,
            'pagos'  => $this->cobranza->pagosDe($folio),
        ]);
    }

    public function editarPago(int $idPago)
    {
        $pago = $this->cobranza->find($idPago);
        if ($pago === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $avaluo = $this->cobranza->datosAvaluo($pago['Folio']);

        return view('cobranza/editar_pago', [
            'titulo' => 'Modificar Pago',
            'pago'   => $pago,
            'avaluo' => $avaluo,
            'pagos'  => $this->cobranza->pagosDe($pago['Folio']),
        ]);
    }

    public function actualizarPago(int $idPago)
    {
        $pago = $this->cobranza->find($idPago);
        if ($pago === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ((int) $this->request->getPost('opc') === 1) {
            $nuevoImporte = (float) $this->request->getPost('Pago');
            $avaluo       = $this->cobranza->datosAvaluo($pago['Folio']);
            $otrosPagos   = array_sum(array_map(
                static fn ($p) => (int) $p['IdPago'] === $idPago ? 0 : (float) $p['Importe'],
                $this->cobranza->pagosDe($pago['Folio'])
            ));

            if ($nuevoImporte <= 0 || ($otrosPagos + $nuevoImporte) > (float) $avaluo['Honorarios']) {
                return redirect()->to(site_url('cobranza/pago/' . $idPago . '/editar'))
                    ->with('error', 'El Pago Excede el saldo por Cobrar');
            }

            $this->cobranza->update($idPago, ['Importe' => $nuevoImporte]);
        }

        return redirect()->to(site_url('cobranza/' . $pago['Folio'] . '/ver'))->with('mensaje', 'Datos Actualizados Correctamente');
    }

    public function confirmarEliminarPago(int $idPago)
    {
        $pago = $this->cobranza->find($idPago);
        if ($pago === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('cobranza/eliminar_pago', [
            'titulo' => 'Eliminar Pago',
            'pago'   => $pago,
        ]);
    }

    public function eliminarPago(int $idPago)
    {
        $pago = $this->cobranza->find($idPago);
        if ($pago === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ((int) $this->request->getPost('opc') === 1) {
            $this->cobranza->delete($idPago);
        }

        return redirect()->to(site_url('cobranza/' . $pago['Folio'] . '/ver'))->with('mensaje', 'Datos Eliminados Correctamente');
    }
}
