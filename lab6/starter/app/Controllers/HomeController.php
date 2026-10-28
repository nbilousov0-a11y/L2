<?php
// app/Controllers/HomeController.php — контроллер главной страницы.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\BookingRepository;
use App\Models\TourRepository;

final class HomeController
{
    // GET /
    public function index(): void
    {
        $bookings = (new BookingRepository())->all();
        $tours = (new TourRepository())->all();

        $byStatus = ['new' => 0, 'confirmed' => 0, 'paid' => 0, 'cancelled' => 0];
        foreach ($bookings as $b) {
            if (isset($byStatus[$b['status']])) {
                $byStatus[$b['status']]++;
            }
        }

        view('home', [
            'title'      => 'Главная',
            'total'      => count($bookings),
            'byStatus'   => $byStatus,
            'toursCount' => count($tours),
        ]);
    }
}