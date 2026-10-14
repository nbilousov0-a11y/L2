<?php
// routes/web.php — таблица маршрутов

use App\Controllers\HomeController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;

// Главная
$router->get('/', [HomeController::class, 'index']);

// Задачи
$router->get('/tasks',         [TaskController::class, 'index']);
$router->get('/tasks/create',  [TaskController::class, 'create']);
$router->post('/tasks',        [TaskController::class, 'store']);
$router->get('/tasks/{id}',    [TaskController::class, 'show']);

// Проекты
$router->get('/projects',      [ProjectController::class, 'index']);
$router->get('/projects/{id}', [ProjectController::class, 'show']);