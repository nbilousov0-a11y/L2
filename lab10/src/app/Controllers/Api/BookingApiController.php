<?php
namespace App\Controllers\Api;

use App\Models\BookingRepository;
use App\Core\Cache; // Подключаем наш кеш

class BookingApiController
{
    public function index()
    {
        $cacheKey = 'bookings:all';

        // 1. Проверяем кеш
        $cachedData = Cache::get($cacheKey);
        if ($cachedData !== null) {
            header('X-Cache: HIT');
            json_response($cachedData);
        }

        // 2. Идем в базу
        $repo = new BookingRepository();
        $bookings = $repo->all();

        // 3. Сохраняем в кеш
        Cache::set($cacheKey, $bookings);

        header('X-Cache: MISS');
        json_response($bookings);
    }

    public function show($id)
    {
        $repo = new BookingRepository();
        $booking = $repo->find((int)$id);
        if (!$booking) json_error('Не найдено', 404);
        json_response($booking);
    }

    public function store()
    {
        // ИНВАЛИДАЦИЯ: при добавлении новой брони удаляем старый список из кеша
        Cache::delete('bookings:all');
        
        // ... (здесь твой код добавления в базу, если он есть)
        // $repo = new BookingRepository();
        // $repo->add($_POST);
        
        json_response(['status' => 'ok'], 201);
    }

    public function update($id)
    {
        // ИНВАЛИДАЦИЯ: при изменении брони сбрасываем кеш списка
        Cache::delete('bookings:all');
        
        // ... (здесь твой код обновления в базе)
        
        json_response(['status' => 'updated']);
    }

    public function destroy($id)
    {
        // ИНВАЛИДАЦИЯ: при удалении брони сбрасываем кеш
        Cache::delete('bookings:all');
        
        // ... (здесь твой код удаления из базы)
        
        json_response(['status' => 'deleted']);
    }
}