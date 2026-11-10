<?php
namespace App\Core;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        $config = require dirname(__DIR__, 2) . '/config/app.php';
        $db = $config['db'];
        
        $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['name']}";
        
        try {
            return new PDO($dsn, $db['user'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            http_response_code(503);
            header('Content-Type: application/json; charset=utf-8');
            // Выводим точную ошибку, чтобы понять, что не так с базой
            echo json_encode(['error' => 'Ошибка БД: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}