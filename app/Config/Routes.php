<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================
// HALAMAN PUBLIC
// ==========================================
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');

// API untuk Peta dan Data
$routes->get('home/getLokasi', 'Home::getLokasi');
$routes->get('home/getDetail/(:num)', 'Home::getDetail/$1');
$routes->get('home/search', 'Home::search');
$routes->get('home/filterKecamatan/(:segment)', 'Home::filterKecamatan/$1');
$routes->get('home/findByRadius', 'Home::findByRadius');
$routes->get('home/getStatistik', 'Home::getStatistik');

// ==========================================
// ADMIN PANEL - CRUD
// ==========================================
$routes->group('admin', function($routes) {
    // Dashboard
    $routes->get('/', 'Admin::index');
    
    // Create
    $routes->get('create', 'Admin::create');
    $routes->post('store', 'Admin::store');
    
    // Edit
    $routes->get('edit/(:num)', 'Admin::edit/$1');
    $routes->post('update/(:num)', 'Admin::update/$1');
    
    // Delete
    $routes->get('delete/(:num)', 'Admin::delete/$1');
    
    // Export
    $routes->get('export', 'Admin::export');
});