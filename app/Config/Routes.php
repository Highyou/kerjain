<?php
// ============================================================
// app/Config/Routes.php — KerjaIn Routing Lengkap
// ============================================================

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// ============================================================
// PUBLIC ROUTES (Tanpa login)
// ============================================================
$routes->get('/',                   'Home::index');
$routes->get('jasa',                'Home::browse');
$routes->get('jasa/(:segment)',     'Home::detail/$1');
$routes->get('kategori/(:segment)', 'Home::byCategory/$1');
$routes->get('cari',                'Home::search');

// Auth
$routes->get ('auth/login',    'AuthController::loginPage');
$routes->post('auth/login',    'AuthController::loginProcess');
$routes->get ('auth/register', 'AuthController::registerPage');
$routes->post('auth/register', 'AuthController::registerProcess');
$routes->get ('auth/logout',   'AuthController::logout');

// ============================================================
// BUYER ROUTES (Login → role: buyer)
// ============================================================
$routes->group('buyer', ['filter' => 'auth:buyer'], function($routes) {
    $routes->get('dashboard',                'BuyerController::dashboard');

    // Cart
    $routes->get ('cart',                    'BuyerController::cart');
    $routes->post('cart/add',                'BuyerController::cartAdd');
    $routes->post('cart/remove/(:num)',      'BuyerController::cartRemove/$1');
    $routes->post('cart/clear',              'BuyerController::cartClear');

    // Checkout & Order
    $routes->get ('checkout',                'BuyerController::checkout');
    $routes->post('checkout/process',        'BuyerController::checkoutProcess');
    $routes->get ('orders',                  'BuyerController::orders');
    $routes->get ('orders/(:num)',            'BuyerController::orderDetail/$1');
    $routes->post('orders/selesai/(:num)', 'BuyerController::orderSelesai/$1');


    // Review
    $routes->get ('review/(:num)',            'BuyerController::reviewPage/$1');
    $routes->post('review/submit',            'BuyerController::reviewSubmit');

    // Profil
    $routes->get ('profile',                 'BuyerController::profile');
    $routes->post('profile/update',          'BuyerController::profileUpdate');
});

// ============================================================
// SELLER ROUTES (Login → role: seller)
// ============================================================
$routes->group('seller', ['filter' => 'auth:seller'], function($routes) {
    $routes->get('dashboard',                'SellerController::dashboard');

    // Kelola Jasa
    $routes->get ('jasa',                    'SellerController::jasaList');
    $routes->get ('jasa/tambah',             'SellerController::jasaCreate');
    $routes->post('jasa/simpan',             'SellerController::jasaStore');
    $routes->get ('jasa/edit/(:num)',         'SellerController::jasaEdit/$1');
    $routes->post('jasa/update/(:num)',       'SellerController::jasaUpdate/$1');
    $routes->post('jasa/hapus/(:num)',        'SellerController::jasaDelete/$1');

    // Pesanan
    $routes->get ('pesanan',                 'SellerController::pesananList');
    $routes->get ('pesanan/(:num)',           'SellerController::pesananDetail/$1');
    $routes->post('pesanan/update-status',   'SellerController::pesananUpdateStatus');
    $routes->post('pesanan/upload-file',     'SellerController::pesananUploadFile');

    // Profil Seller
    $routes->get ('profile',                 'SellerController::profile');
    $routes->post('profile/update',          'SellerController::profileUpdate');
    $routes->get ('earnings',                'SellerController::earnings');
});

// ============================================================
// ADMIN ROUTES (Login → role: admin)
// ============================================================
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard',                'AdminController::dashboard');

    // User Management
    $routes->get ('users',                   'AdminController::users');
    $routes->get ('users/(:num)',             'AdminController::userDetail/$1');
    $routes->post('users/toggle/(:num)',      'AdminController::userToggle/$1');
    $routes->post('users/hapus/(:num)',       'AdminController::userDelete/$1');

    // Jasa Management
    $routes->get ('jasa',                    'AdminController::jasa');
    $routes->post('jasa/approve/(:num)',      'AdminController::jasaApprove/$1');
    $routes->post('jasa/reject/(:num)',       'AdminController::jasaReject/$1');
    $routes->post('jasa/hapus/(:num)',        'AdminController::jasaDelete/$1');

    // Kategori
    $routes->get ('kategori',                'AdminController::kategori');
    $routes->post('kategori/simpan',         'AdminController::kategoriStore');
    $routes->post('kategori/update/(:num)',   'AdminController::kategoriUpdate/$1');
    $routes->post('kategori/hapus/(:num)',    'AdminController::kategoriDelete/$1');

    // Transaksi
    $routes->get('transaksi',               'AdminController::transaksi');
    $routes->get('transaksi/(:num)',          'AdminController::transaksiDetail/$1');
    $routes->post('transaksi/konfirmasi-bayar/(:num)', 'AdminController::konfirmasiBayar/$1');


    // Laporan
    $routes->get('laporan',                  'AdminController::laporan');
    $routes->get('laporan/export',           'AdminController::laporanExport');
});