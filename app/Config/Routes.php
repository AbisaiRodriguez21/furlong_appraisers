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
    $routes->get('usuarios', 'EnConstruccion::index/usuarios', ['filter' => 'role:2']);

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
    $routes->get('avaluos', 'EnConstruccion::index/avaluos');
    $routes->get('cobranza', 'EnConstruccion::index/cobranza', ['filter' => 'role:1,2']);
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
