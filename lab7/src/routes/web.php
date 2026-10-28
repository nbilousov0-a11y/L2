<?php
// routes/web.php — таблица маршрутов

// --- Сайт (ЛР №6) ---
use App\Controllers\HomeController;
use App\Controllers\BookingController;
use App\Controllers\TourController;

// --- REST API (ЛР №7) ---
use App\Controllers\Api\BookingApiController;
use App\Controllers\Api\TourApiController;
use App\Controllers\Api\ClientApiController;

// Главная
$router->get('/', [HomeController::class, 'index']);

// Бронирования (сайт)
$router->get('/bookings',         [BookingController::class, 'index']);
$router->get('/bookings/create',  [BookingController::class, 'create']);
$router->post('/bookings',        [BookingController::class, 'store']);
$router->get('/bookings/{id}',    [BookingController::class, 'show']);

// Туры (сайт)
$router->get('/tours',            [TourController::class, 'index']);
$router->get('/tours/{id}',       [TourController::class, 'show']);


// ==========================================
// REST API (ЛР №7)
// ==========================================

// --- Бронирования ---
$router->get('/api/bookings',         [BookingApiController::class, 'index']);
$router->get('/api/bookings/{id}',    [BookingApiController::class, 'show']);
$router->post('/api/bookings',        [BookingApiController::class, 'store']);
$router->put('/api/bookings/{id}',    [BookingApiController::class, 'update']);
$router->patch('/api/bookings/{id}',  [BookingApiController::class, 'update']);
$router->delete('/api/bookings/{id}', [BookingApiController::class, 'destroy']);

// --- Туры ---
$router->get('/api/tours',            [TourApiController::class, 'index']);
$router->get('/api/tours/{id}',       [TourApiController::class, 'show']);
$router->post('/api/tours',           [TourApiController::class, 'store']);
$router->put('/api/tours/{id}',       [TourApiController::class, 'update']);
$router->patch('/api/tours/{id}',     [TourApiController::class, 'update']);
$router->delete('/api/tours/{id}',    [TourApiController::class, 'destroy']);
$router->get('/api/tours/{id}/bookings', [TourApiController::class, 'bookings']);

// --- Клиенты ---
$router->get('/api/clients',          [ClientApiController::class, 'index']);
$router->get('/api/clients/{id}',     [ClientApiController::class, 'show']);
$router->post('/api/clients',         [ClientApiController::class, 'store']);
$router->put('/api/clients/{id}',     [ClientApiController::class, 'update']);
$router->patch('/api/clients/{id}',   [ClientApiController::class, 'update']);
$router->delete('/api/clients/{id}',  [ClientApiController::class, 'destroy']);