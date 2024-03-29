<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Ongkir::index');
$routes->get('auth', 'Auth::index');
$routes->post('/', 'Auth::index');
$routes->get('sistem/update_resis', 'Sistem::update_resis');
$routes->get('sistem/cekExpired', 'Sistem::cekExpired');
$routes->get('sistem/cekResi', 'Sistem::cekResi');
$routes->get('sistem/cekResi/(:num)/(:num)', 'Sistem::cekResi/$1/$2');
$routes->get('sistem/cekWhatsapp', 'Sistem::cekWhatsapp');
$routes->get('sistem/cekWhatsapp/(:num)/(:num)', 'Sistem::cekWhatsapp/$1/$2');
$routes->get('sistem/cekWhatsappResi', 'Sistem::cekWhatsappResi');
$routes->get('sistem/cekWhatsappResi/(:num)/(:num)', 'Sistem::cekWhatsappResi/$1/$2');
$routes->get('sistem/getResi', 'Sistem::getResi');
$routes->get('admin/logout', 'Auth::logout');

$routes->get('ongkir', 'Ongkir::index');
$routes->get('ongkir/city/(:num)', 'Ongkir::city/$1');
$routes->get('ongkir/subdis/(:num)', 'Ongkir::subdis/$1');
$routes->get('ongkir/cek/(:any)', 'Ongkir::cek/$1/$1/$1/$1/$1/$1');


$routes->group('admin', ["namespace" => "App\Controllers\Admin"], static function ($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('admin/delete/(:num)', 'Admin::delete/$1');
    $routes->resource("admin", ['except' => 'show, new, edit', 'delete']);
    
    $routes->get('produk/delete/(:num)', 'Produk::delete/$1');
    $routes->resource("produk", ['except' => 'show, new, edit', 'delete', 'variasi']);

    $routes->get('variasi/delete/(:num)', 'Variasi::delete/$1');
    $routes->post('variasi/create', 'Variasi::create');
    
    $routes->get('resi/delete/(:num)', 'Resi::delete/$1');
    $routes->resource("resi", ['except' => 'delete']);
    $routes->post('resi/import', 'Resi::import');

    
    $routes->get('ongkir', 'Ongkir::index');
    $routes->get('ongkir/city/(:num)', 'Ongkir::city/$1');
    $routes->get('ongkir/subdis/(:num)', 'Ongkir::subdis/$1');
    $routes->get('ongkir/cek/(:any)', 'Ongkir::cek/$1/$1/$1/$1');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
