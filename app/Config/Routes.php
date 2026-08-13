<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --- Auth (replaces index.php / control.php / salir.php) ---
$routes->get('login', 'Auth::index');
$routes->post('auth/attempt', 'Auth::attempt');
$routes->get('auth/logout', 'Auth::logout');

// --- Protected app (replaces seguridad.php on every page) ---
$routes->group('', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('/', 'Home::index');

    // Same role matrix as the legacy system: 0=Usuario, 1=Administrador, 2=Gerente General.
    // --- Usuarios (replaces Usuarios.php + Usuarios_Agregar/Consultar/Modificar/ModificarPassw/Eliminar.php) ---
    $routes->group('usuarios', ['filter' => 'role:2'], static function ($routes): void {
        $routes->get('/', 'Usuarios::index');
        $routes->get('filtro', 'Usuarios::filtro');
        $routes->get('nuevo', 'Usuarios::nuevo');
        $routes->post('guardar', 'Usuarios::guardar');
        $routes->get('(:num)', 'Usuarios::mostrar/$1');
        $routes->get('(:num)/editar', 'Usuarios::editar/$1');
        $routes->post('(:num)/actualizar', 'Usuarios::actualizar/$1');
        $routes->get('(:num)/eliminar', 'Usuarios::confirmarEliminar/$1');
        $routes->post('(:num)/eliminar', 'Usuarios::eliminar/$1');
    });

    // --- Clientes (replaces Clientes.php + Clientes_Agregar/Consultar/Modificar/Eliminar.php) ---
    $routes->get('clientes', 'Clientes::index');
    $routes->get('clientes/filtro', 'Clientes::filtro');
    $routes->get('clientes/nuevo', 'Clientes::nuevo');
    $routes->post('clientes/guardar', 'Clientes::guardar');
    $routes->get('clientes/(:num)', 'Clientes::mostrar/$1');
    $routes->get('clientes/(:num)/editar', 'Clientes::editar/$1');
    $routes->post('clientes/(:num)/actualizar', 'Clientes::actualizar/$1');
    $routes->get('clientes/(:num)/eliminar', 'Clientes::confirmarEliminar/$1');
    $routes->post('clientes/(:num)/eliminar', 'Clientes::eliminar/$1');

    // --- Trabajos (replaces Trabajos.php + AgregarTrabajo/ConsultarTrabajo/ModificarTrabajo/EliminarTrabajo.php) ---
    $routes->get('trabajos', 'Trabajos::index');
    $routes->get('trabajos/filtro', 'Trabajos::filtro');
    $routes->get('trabajos/nuevo', 'Trabajos::nuevo');
    $routes->post('trabajos/guardar', 'Trabajos::guardar');
    $routes->get('trabajos/(:num)', 'Trabajos::mostrar/$1');
    $routes->get('trabajos/(:num)/editar', 'Trabajos::editar/$1');
    $routes->post('trabajos/(:num)/actualizar', 'Trabajos::actualizar/$1');
    $routes->get('trabajos/(:num)/eliminar', 'Trabajos::confirmarEliminar/$1');
    $routes->post('trabajos/(:num)/eliminar', 'Trabajos::eliminar/$1');
    // --- Avalúos (replaces Avaluos.php + AgregarAvaluo/ConsultarAvaluo/ModificarAvaluo.php + ver.php) ---
    $routes->get('avaluos', 'Avaluos::index');
    $routes->get('avaluos/filtro', 'Avaluos::filtro');
    $routes->get('avaluos/nuevo', 'Avaluos::nuevo');
    // --- Semanas (shared by both report pickers, replaces Semanas.php) ---
    $routes->get('semanas', 'Semanas::opciones');
    $routes->get('semanas/generar', 'Semanas::generar');

    // --- Reportes de Avalúos (replaces AvaluosReportes*.php + AvaluosReporteHoja.php + AvaluosGral*Hoja.php) ---
    $routes->group('avaluos/reportes', ['filter' => 'role:2'], static function ($routes): void {
        $routes->get('/', 'ReportesAvaluos::index');
        $routes->get('picker/(:num)', 'ReportesAvaluos::picker/$1');
        $routes->post('lista', 'ReportesAvaluos::lista');
        $routes->get('lista/(:num)', 'ReportesAvaluos::listaDirecta/$1');
        $routes->post('pdf', 'ReportesAvaluos::pdf');
        $routes->get('general/(:num)', 'ReportesAvaluos::generalPorStatus/$1');
        $routes->post('general/pdf', 'ReportesAvaluos::generalPorStatusPdf');
        $routes->get('general-trabajo/(:num)', 'ReportesAvaluos::generalPorTrabajo/$1');
        $routes->post('general-trabajo/pdf', 'ReportesAvaluos::generalPorTrabajoPdf');
    });
    $routes->get('avaluos/documentos-de-trabajo/(:num)', 'Avaluos::documentosDeTrabajo/$1');
    $routes->post('avaluos/guardar', 'Avaluos::guardar');
    $routes->post('avaluos/(:any)/cancelar', 'Avaluos::cancelar/$1');
    $routes->get('avaluos/(:any)/imagen', 'Avaluos::imagen/$1');
    $routes->get('avaluos/(:any)/editar', 'Avaluos::editar/$1');
    $routes->post('avaluos/(:any)/actualizar', 'Avaluos::actualizar/$1');
    $routes->get('avaluos/(:any)', 'Avaluos::mostrar/$1');
    // --- Cobranza (replaces Cobranza.php + CobranzaPagar/CobranzaVer/CobranzaModifP.php + eliminarPago.php) ---
    $routes->group('cobranza', ['filter' => 'role:1,2'], static function ($routes): void {
        $routes->get('/', 'Cobranza::index');
        $routes->get('filtro', 'Cobranza::filtro');
        // --- Reportes de Cobranza (replaces CobranzaReporte*.php + CobranzaInfo*.php) ---
        $routes->group('reportes', ['filter' => 'role:2'], static function ($routes): void {
            $routes->get('/', 'ReportesCobranza::index');
            $routes->get('dia', 'ReportesCobranza::dia');
            $routes->get('dia/datos', 'ReportesCobranza::diaDatos');
            $routes->post('dia/pdf', 'ReportesCobranza::diaPdf');
            $routes->get('cliente/(:num)', 'ReportesCobranza::porCliente/$1');
            $routes->get('cliente/datos', 'ReportesCobranza::porClienteDatos');
            $routes->post('cliente/pdf', 'ReportesCobranza::porClientePdf');
            $routes->get('general/(:num)', 'ReportesCobranza::general/$1');
            $routes->post('general/lista', 'ReportesCobranza::generalLista');
            $routes->post('general/pdf', 'ReportesCobranza::generalPdf');
            $routes->get('trabajo', 'ReportesCobranza::porTrabajo');
            $routes->post('trabajo/lista', 'ReportesCobranza::porTrabajoLista');
            $routes->post('trabajo/pdf', 'ReportesCobranza::porTrabajoPdf');
        });
        $routes->get('pago/(:num)/editar', 'Cobranza::editarPago/$1');
        $routes->post('pago/(:num)/actualizar', 'Cobranza::actualizarPago/$1');
        $routes->get('pago/(:num)/eliminar', 'Cobranza::confirmarEliminarPago/$1');
        $routes->post('pago/(:num)/eliminar', 'Cobranza::eliminarPago/$1');
        $routes->get('(:any)/pagar', 'Cobranza::pagar/$1');
        $routes->post('(:any)/pagar', 'Cobranza::pagarGuardar/$1');
        $routes->get('(:any)/ver', 'Cobranza::ver/$1');
    });
    // --- Recibos de Honorarios (replaces Recibos.php + ReciboHonorarios/Cambiar/Cancelar/Consulta.php + ReciboHonorarios_Proceso.php) ---
    $routes->group('recibos', ['filter' => 'role:1,2'], static function ($routes): void {
        $routes->get('/', 'Recibos::index');
        $routes->get('nuevo', 'Recibos::nuevo');
        $routes->post('guardar', 'Recibos::guardar');
        $routes->get('(:num)', 'Recibos::consultar/$1');
        $routes->get('(:num)/editar', 'Recibos::editar/$1');
        $routes->post('(:num)/actualizar', 'Recibos::actualizar/$1');
        $routes->get('(:num)/cancelar', 'Recibos::confirmarCancelar/$1');
        $routes->post('(:num)/cancelar', 'Recibos::cancelar/$1');
    });
    // --- Documentación (replaces Documentacion.php + AgregarDocumentacion/ModificarDocumentacion/EliminarDocumentacion.php) ---
    $routes->get('documentacion', 'Documentacion::index');
    $routes->get('documentacion/filtro', 'Documentacion::filtro');
    $routes->get('documentacion/nuevo', 'Documentacion::nuevo');
    $routes->post('documentacion/guardar', 'Documentacion::guardar');
    $routes->get('documentacion/(:num)/editar', 'Documentacion::editar/$1');
    $routes->post('documentacion/(:num)/actualizar', 'Documentacion::actualizar/$1');
    $routes->get('documentacion/(:num)/eliminar', 'Documentacion::confirmarEliminar/$1');
    $routes->post('documentacion/(:num)/eliminar', 'Documentacion::eliminar/$1');
    $routes->get('ayuda', 'EnConstruccion::index/ayuda');
});
