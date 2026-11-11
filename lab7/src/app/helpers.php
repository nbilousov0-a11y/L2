<?php
// app/helpers.php — небольшие вспомогательные функции для всего приложения.

declare(strict_types=1);

// Читает настройку из config/app.php: config('name'), config('debug')
function config(string $key): mixed
{
    static $config = null;
    $config ??= require BASE_PATH . '/config/app.php';
    return $config[$key] ?? null;
}

// Безопасный вывод текста в HTML: заменяет символы < > & " на безопасные коды.
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// Показывает страницу: шапка + шаблон + подвал.
function view(string $template, array $data = []): void
{
    extract($data);
    require APP_PATH . '/Views/layout/header.php';
    require APP_PATH . '/Views/' . $template . '.php';
    require APP_PATH . '/Views/layout/footer.php';
}

// Перенаправляет браузер на другой адрес и завершает работу скрипта.
function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

// Показывает страницу «Не найдено» с кодом ответа 404.
function notFound(): never
{
    http_response_code(404);
    view('errors/404', ['path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)]);
    exit;
}


// ============================================
// Функции для работы с API (JSON) - Лабораторная №7
// ============================================

function json_response(mixed $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $status, array $errors = []): never
{
    $body = ['error' => $message];
    if ($errors) $body['errors'] = $errors;
    json_response($body, $status);
}

function no_content(): never
{
    http_response_code(204);
    exit;
}

function json_input(): array
{
    $raw = file_get_contents('php://input');
    if (trim($raw) === '') return [];
    $data = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        json_error('Некорректный JSON в теле запроса', 400);
    }
    return $data;
}