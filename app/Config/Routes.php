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
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

$routes->get('/pagenotfound', 'Document::pagenotfound');
$routes->get('/', 'Admin::signin');
$routes->get('/signin', 'Admin::signin');
//$routes->addRedirect('/signin', 'signin');

$routes->post('/signin', 'Admin::signin');
$routes->get('/signup', 'Admin::signup');
$routes->post('/signup', 'Admin::signup');
$routes->get('/verify', 'Admin::accountverify');
$routes->post('/verify', 'Admin::accountverify');
$routes->get('/upload', 'Document::upload');
$routes->post('/file-upload', 'Document::FileUpload');
$routes->post('/file-delete', 'Document::FileDelete');
//$routes->get('/prepare/(:num)', 'Document::prepare/$1');
$routes->get('/prepare/(:any)', 'Document::prepare/$1');
$routes->post('/fileupload', 'Document::fileupload');
$routes->post('/filedelete', 'Document::filedelete');
$routes->post('/send', 'Document::saveandsenddocument');
$routes->get('/sign', 'Document::sign');
$routes->post('/verifyaccesscode', 'Document::sign');

//$routes->get('/processsign', 'Document::processsign');


$routes->post('/processsign', 'Document::processsign');
$routes->post('/writesigndata', 'Document::writesigndata');
$routes->post('/sendDocAccessOtp', 'Document::sendDocAccessOtp');

$routes->get('/processsignstatic', 'Document::processsignstatic');
$routes->get('emailengine/sendOtpEmail/(:segment)', 'Emailengine::sendOtpEmail/$1');
$routes->get('/dashboard', 'Document::dashboard');
$routes->get('/signeddocument/(:any)', 'Document::signeddocument/$1');
$routes->get('/logout', 'Admin::logout');

$routes->cli('emailengine/sendDocuSingColl/(:any)', 'Emailengine::sendDocuSingColl/$1');
$routes->cli('emailengine/sendCompletedDocumentToSigner/(:any)', 'Emailengine::sendCompletedDocumentToSigner/$1');
$routes->cli('emailengine/sendDocuExpiredOwner/(:any)', 'Emailengine::sendDocuExpiredOwner/$1');
$routes->cli('/getExpireDocuments', 'Crons::getExpireDocuments');

//test routes
$routes->get('emailengine/sendCompletedDocumentToSigner/(:any)', 'Emailengine::sendCompletedDocumentToSigner/$1');
$routes->get('emailengine/sendDocuSingColl/(:any)', 'Emailengine::sendDocuSingColl/$1');
$routes->get('/test', 'Document::test');
$routes->get('/prepareConsolidatePdfs/(:any)', 'Document::prepareConsolidatePdfs/$1');
$routes->get('/getExpireDocuments', 'Crons::getExpireDocuments');
$routes->get('emailengine/sendDocuExpiredOwner/(:any)', 'Emailengine::sendDocuExpiredOwner/$1');



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
