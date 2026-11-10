<?php
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error($message, $status = 400) {
    json_response(['error' => $message], $status);
}

// Вспомогательная функция для подключения классов без Composer
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file = dirname(__DIR__) . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) require $file;
});
function require_auth() {
    // Получаем заголовки запроса
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? '';
    
    // Если нет заголовка или он не начинается с "Bearer " — отказ 401
    if (!$auth || strpos($auth, 'Bearer ') !== 0) {
        json_error('Требуется авторизация', 401);
    }
    
    // В реальном проекте здесь был бы поиск токена в базе. 
    // Для простоты сделаем вид, что любой токен правильный.
    return true;
}