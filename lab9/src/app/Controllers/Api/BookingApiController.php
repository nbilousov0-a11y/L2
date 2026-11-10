<?php
namespace App\Controllers\Api;

use App\Models\BookingRepository;

class BookingApiController
{
    public function index()
    {
        $repo = new BookingRepository();
        $bookings = $repo->all();
        json_response($bookings);
    }

    public function show($id)
    {
        $repo = new BookingRepository();
        $booking = $repo->find((int)$id);
        if (!$booking) {
            json_error('Бронирование не найдено', 404);
        }
        json_response($booking);
    }
}