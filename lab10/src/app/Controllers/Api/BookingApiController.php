<?php
namespace App\Controllers\Api;

use App\Models\BookingRepository;
use App\Core\Cache; // Наш кеш

class BookingApiController
{
    public function index()
    {
        $cacheKey = 'bookings:all';

        // 1. Проверяем кеш
        $cachedData = Cache::get($cacheKey);
        if ($cachedData !== null) {
            header('X-Cache: HIT'); // Из кеша!
            json_response($cachedData);
        }

        // 2. Идем в базу
        $repo = new BookingRepository();
        $bookings = $repo->all();

        // 3. Сохраняем в кеш
        Cache::set($cacheKey, $bookings);

        header('X-Cache: MISS'); // Из базы!
        json_response($bookings);
    }

    public function show($id)
    {
        $repo = new BookingRepository();
        $booking = $repo->find((int)$id);
        if (!$booking) json_error('Не найдено', 404);
        json_response($booking);
    }
}