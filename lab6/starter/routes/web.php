<?php
// routes/web.php — таблица маршрутов

use App\Controllers\HomeController;
use App\Controllers\BookingController;
use App\Controllers\TourController;

// Главная
$router->get('/', [HomeController::class, 'index']);

// Бронирования
$router->get('/bookings',         [BookingController::class, 'index']);
$router->get('/bookings/create',  [BookingController::class, 'create']);
$router->post('/bookings',        [BookingController::class, 'store']);
$router->get('/bookings/{id}',    [BookingController::class, 'show']);

// Туры
$router->get('/tours',            [TourController::class, 'index']);
$router->get('/tours/{id}',       [TourController::class, 'show']);