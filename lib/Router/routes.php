<?php

use App\Controllers\AuthController;
use App\Controllers\CompanyController;
use App\Controllers\ConstantController;
use App\Controllers\DashboardController;
use App\Controllers\DatabaseController;
use App\Controllers\RoutesController;
use App\Controllers\AgendaController;
use App\Controllers\AgendaDetailsController;
use App\Controllers\DirectorController;
use App\Middleware\AuthMiddleware;
use LIB\Router\Router;
use LIB\Request\Request;

$router = new Router();

$router->get('/', function () {redirect(Router::HOME);});
$router->get('/admin', function () {redirect(Router::HOME);});


$router->get('/admin/login', [AuthController::class, 'login']);
$router->get('/admin/dashboard', [DashboardController::class, 'index'], new AuthMiddleware('uname'));
$router->get('/admin/admin-tools', [RoutesController::class, 'adminTools'], new AuthMiddleware('uname'));
$router->get('/admin/manage-company', [RoutesController::class, 'manageCompany'], new AuthMiddleware('uname'));
$router->get('/admin/meeting-constants', [RoutesController::class, 'meetingConstants'], new AuthMiddleware('uname'));
$router->get('/admin/system-constants', [RoutesController::class, 'systemConstants'], new AuthMiddleware('uname'));
$router->get('/admin/agendas/view', [RoutesController::class, 'agendas'], new AuthMiddleware('uname'));

/* Company */
$router->get('/admin/company', [CompanyController::class, 'getAll'], new AuthMiddleware('uname'));
$router->put('/admin/company', [CompanyController::class, 'update'], new AuthMiddleware('uname'));


/* Constants */
$router->get('/admin/constants/meeting', [ConstantController::class, 'meetingIndex'], new AuthMiddleware('uname'));
$router->put('/admin/constants/meeting', [ConstantController::class, 'meetingUpdate'], new AuthMiddleware('uname'));

$router->get('/admin/constants/system', [ConstantController::class, 'systemIndex'], new AuthMiddleware('uname'));
$router->put('/admin/constants/system', [ConstantController::class, 'systemUpdate'], new AuthMiddleware('uname'));

/* Agendas */
$router->get('/admin/agendas', [AgendaController::class, 'index'], new AuthMiddleware('uname'));
$router->post('/admin/agendas', [AgendaController::class, 'store'], new AuthMiddleware('uname'));
$router->put('/admin/agendas', [AgendaController::class, 'update'], new AuthMiddleware('uname'));
$router->delete('/admin/agendas', [AgendaController::class, 'truncate'], new AuthMiddleware('uname'));

/* Directors */
$router->get('/admin/directors', [DirectorController::class, 'index'], new AuthMiddleware('uname'));
$router->post('/admin/directors', [DirectorController::class, 'store'], new AuthMiddleware('uname'));
$router->put('/admin/directors', [DirectorController::class, 'update'], new AuthMiddleware('uname'));
$router->delete('/admin/directors', [DirectorController::class, 'truncate'], new AuthMiddleware('uname'));

/* AgendaDetails */
$router->get('/admin/agenda-details', [AgendaDetailsController::class, 'index'], new AuthMiddleware('uname'));
$router->post('/admin/agenda-details', [AgendaDetailsController::class, 'store'], new AuthMiddleware('uname'));
$router->put('/admin/agenda-details', [AgendaDetailsController::class, 'update'], new AuthMiddleware('uname'));
$router->delete('/admin/agenda-details', [AgendaDetailsController::class, 'truncate'], new AuthMiddleware('uname'));

$router->post('/database/truncate', [DatabaseController::class, 'truncate'], new AuthMiddleware('uname'));
$router->post('/admin/login', [AuthController::class, 'auth']);
$router->post('/admin/logout', [AuthController::class, 'logout']);

$router->run();
