<?php
require_once dirname(__DIR__) . '/app/helpers.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Охранник: если стучатся к API, проверяем наличие токена
if (str_starts_with($uri, '/api')) {
    require_auth();
}

// Маршрутизация
if ($uri === '/api/bookings' && $method === 'GET') {
    $controller = new App\Controllers\Api\BookingApiController();
    $controller->index();
} 
elseif (preg_match('#^/api/bookings/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $controller = new App\Controllers\Api\BookingApiController();
    $controller->show($matches[1]);
} 
else {
    json_error('Страница не найдена', 404);
}