<?php
// public/index.php — единая точка входа (front controller).
// Веб-сервер отправляет сюда ВСЕ запросы, а дальше работает наш маршрутизатор.

declare(strict_types=1);

// TODO 1.1. Пути к каталогам проекта
define('BASE_PATH', dirname(__DIR__));        // корень проекта (starter/)
define('APP_PATH', BASE_PATH . '/app');       // каталог app/

// Подключаем автозагрузку классов и вспомогательные функции
require_once APP_PATH . '/autoload.php';
require_once APP_PATH . '/helpers.php';

// TODO 1.2. Создаём маршрутизатор, подключаем маршруты и запускаем обработку
$router = new App\Core\Router();
require BASE_PATH . '/routes/web.php';

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code(500);
    view('errors/500', ['message' => $e->getMessage()]);
}