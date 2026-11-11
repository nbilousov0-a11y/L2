<?php
// public/index.php — единая точка входа (front controller).

declare(strict_types=1);

// Пути к каталогам проекта
define('BASE_PATH',    dirname(__DIR__));
define('APP_PATH',     BASE_PATH . '/app');
define('VIEWS_PATH',   APP_PATH . '/Views');
define('STORAGE_PATH', BASE_PATH . '/storage');
define('CONFIG_PATH',  BASE_PATH . '/config');

// Автозагрузка классов и вспомогательные функции
require_once APP_PATH . '/autoload.php';
require_once APP_PATH . '/helpers.php';

// Маршрутизатор
$router = new App\Core\Router();
require BASE_PATH . '/routes/web.php';

// Обработка запроса
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code(500);
    view('errors/500', ['message' => $e->getMessage()]);
}
