<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/rh', 'Home::rh');

$routes->get('/employe', 'Home::employe');
$routes->get('/employe/dashboard', 'Home::employeDashboard');
$routes->get('/employe/create', 'Home::create');

$routes->get('/admin', 'Home::admin');
$routes->get('/admin/dashboard', 'Home::adminDashboard');
