<?php
// app/Controllers/TourController.php — контроллер туров.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TourRepository;

final class TourController
{
    private TourRepository $tours;

    public function __construct()
    {
        $this->tours = new TourRepository();
    }

    // GET /tours
    public function index(): void
    {
        view('tours/index', [
            'title' => 'Туры',
            'tours' => $this->tours->all(),
        ]);
    }

    // GET /tours/2
    public function show(string $id): void
    {
        $tour = $this->tours->find((int) $id);
        if ($tour === null) {
            notFound();
        }

        view('tours/show', [
            'title' => $tour['title'],
            'tour'  => $tour,
        ]);
    }
}