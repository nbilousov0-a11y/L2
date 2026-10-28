<?php
// app/Controllers/BookingController.php — контроллер бронирований.

declare(strict_types=1);

namespace App\Controllers;

use App\Models\BookingRepository;
use App\Models\TourRepository;


final class BookingController
{
    private BookingRepository $bookings;
    private TourRepository $tours;

    private array $statusNames = [
        'new'       => 'Новое',
        'confirmed' => 'Подтверждено',
        'paid'      => 'Оплачено',
        'cancelled' => 'Отменено',
    ];

    public function __construct()
    {
        $this->bookings = new BookingRepository();
        $this->tours = new TourRepository();
    }

    // GET /bookings  или  GET /bookings?status=paid
    public function index(): void
    {
        $status = $_GET['status'] ?? '';
        $bookings = $this->bookings->all();

        if ($status !== '') {
            $bookings = array_values(array_filter(
                $bookings,
                fn(array $b): bool => $b['status'] === $status
            ));
        }

        view('bookings/index', [
            'title'       => 'Бронирования',
            'bookings'    => $bookings,
            'status'      => $status,
            'statusNames' => $this->statusNames,
            'tours'       => $this->tours->all(),
        ]);
    }

    // GET /bookings/5
    public function show(string $id): void
    {
        $booking = $this->bookings->find((int) $id);
        if ($booking === null) {
            notFound();
        }

        view('bookings/show', [
            'title'       => $booking['title'],
            'booking'     => $booking,
            'tour'        => $this->tours->find((int) $booking['tour_id']),
            'statusNames' => $this->statusNames,
        ]);
    }

    // GET /bookings/create
    public function create(): void
    {
        view('bookings/create', [
            'title'       => 'Новое бронирование',
            'tours'       => $this->tours->all(),
            'statusNames' => $this->statusNames,
            'errors'      => [],
            'old'         => [],
        ]);
    }

    // POST /bookings
    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $tourId = (int) ($_POST['tour_id'] ?? 0);
        $status = $_POST['status'] ?? 'new';

        $errors = [];
        if (mb_strlen($title) < 3) {
            $errors['title'] = 'Название — не короче 3 символов';
        }
        if ($this->tours->find($tourId) === null) {
            $errors['tour_id'] = 'Выберите тур из списка';
        }
        if (!isset($this->statusNames[$status])) {
            $errors['status'] = 'Недопустимый статус';
        }

        if ($errors) {
            http_response_code(422);
            view('bookings/create', [
                'title'       => 'Новое бронирование',
                'tours'       => $this->tours->all(),
                'statusNames' => $this->statusNames,
                'errors'      => $errors,
                'old'         => $_POST,
            ]);
            return;
        }

        $this->bookings->add([
            'tour_id' => $tourId,
            'title'   => $title,
            'status'  => $status,
        ]);

        redirect('/bookings');
    }
}