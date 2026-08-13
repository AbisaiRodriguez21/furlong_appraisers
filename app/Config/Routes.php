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

    $routes->get('trabajos', 'EnConstruccion::index/trabajos');
    $routes->get('avaluos', 'EnConstruccion::index/avaluos');
    $routes->get('cobranza', 'EnConstruccion::index/cobranza', ['filter' => 'role:1,2']);
    $routes->get('documentacion', 'EnConstruccion::index/documentacion');
    $routes->get('ayuda', 'EnConstruccion::index/ayuda');
});
