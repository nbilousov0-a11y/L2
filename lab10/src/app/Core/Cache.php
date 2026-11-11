<?php
namespace App\Core;

class Cache
{
    private static function getCacheDir(): string
    {
        $dir = dirname(__DIR__, 2) . '/cache';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    public static function get(string $key)
    {
        $file = self::getCacheDir() . '/' . md5($key) . '.json';
        if (!file_exists($file)) return null;
        
        // TTL 60 секунд
        if (time() - filemtime($file) > 60) {
            unlink($file);
            return null;
        }
        return json_decode(file_get_contents($file), true);
    }

    public static function set(string $key, $value): void
    {
        $file = self::getCacheDir() . '/' . md5($key) . '.json';
        file_put_contents($file, json_encode($value));
    }

    // ТОТ САМЫЙ МЕТОД ДЛЯ ИНВАЛИДАЦИИ (УДАЛЕНИЯ КЕША)
    public static function delete(string $key): void
    {
        $file = self::getCacheDir() . '/' . md5($key) . '.json';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}