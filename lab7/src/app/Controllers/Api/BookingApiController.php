<?php
namespace App\Controllers\Api;

use App\Models\BookingRepository;
use App\Models\TourRepository;
use function json_response;
use function json_error;
use function json_input;
use function no_content;

final class BookingApiController
{
    private BookingRepository $bookings;
    private TourRepository $tours;

    public function __construct()
    {
        $this->bookings = new BookingRepository();
        $this->tours = new TourRepository();
    }

    public function index(): void
    {
        $list = $this->bookings->all();
        json_response($list);
    }

    public function show(string $id): void
    {
        $booking = $this->bookings->find((int) $id);
        if ($booking === null) {
            json_error('Бронирование не найдено', 404);
        }
        json_response($booking);
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $clean = $this->clean($data);
        $clean['status'] = $clean['status'] ?? 'new';
        $created = $this->bookings->add($clean);

        header('Location: /api/bookings/' . $created['id'], true, 201);
        json_response($created, 201);
    }

    public function update(string $id): void
    {
        $booking = $this->bookings->find((int) $id);
        if ($booking === null) {
            json_error('Бронирование не найдено', 404);
        }

        $data = json_input();
        $merged = array_merge($booking, $this->clean($data));

        $errors = $this->validate($merged);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $updated = $this->bookings->update((int) $id, $merged);
        json_response($updated);
    }

    public function destroy(string $id): void
    {
        $booking = $this->bookings->find((int) $id);
        if ($booking === null) {
            json_error('Бронирование не найдено', 404);
        }
        $this->bookings->delete((int) $id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $title = $data['title'] ?? '';
        if (!is_string($title) || mb_strlen(trim($title)) < 3) {
            $errors['title'] = 'Название — не короче 3 символов';
        }
        $tourId = $data['tour_id'] ?? null;
        if ($tourId === null || $this->tours->find((int) $tourId) === null) {
            $errors['tour_id'] = 'Тур не найден (передайте число)';
        }
        $status = $data['status'] ?? 'new';
        if (!in_array($status, ['new', 'confirmed', 'paid', 'cancelled'], true)) {
            $errors['status'] = 'Статус: new, confirmed, paid, cancelled';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        $allowed = ['title', 'tour_id', 'status'];
        return array_intersect_key($data, array_flip($allowed));
    }
}