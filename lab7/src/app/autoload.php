<?php
spl_autoload_register(function ($class) {
    // Префикс наших классов
    $prefix = 'App\\';

    // Если класс не начинается с App\, пропускаем
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Убираем App\ из имени класса
    $relative_class = substr($class, strlen($prefix));

    // Формируем путь: app/Controllers/Api/BookingApiController.php
    $file = APP_PATH . '/' . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});