<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\CongeController;

/**
 * @var RouteCollection $routes
 */

$routes->get('/rh', 'CongeController::findAll');
$routes->post('/rh/approuver', 'CongeController::approuver');
$routes->post('/rh/refuser', 'CongeController::refuser');

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/unauthorized', 'Home::unauthorized');

// ─── Employé ─────────────────────────────────────────────────────────────────
$routes->group('/employe', ['filter' => 'auth:role:employe'], function ($routes) {
    $routes->get('/', 'Home::employe');
    $routes->get('dashboard', 'Home::employeDashboard');
    $routes->get('create', 'Home::create');
});

// ─── Responsable RH ──────────────────────────────────────────────────────────
$routes->group('/rh', ['filter' => 'auth:role:rh'], function ($routes) {
    $routes->get('/', 'Home::rh');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
$routes->group('/admin', ['filter' => 'auth:role:admin'], function ($routes) {
    $routes->get('/', 'Home::admin');
    $routes->get('dashboard', 'Home::adminDashboard');
});