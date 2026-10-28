<?php
namespace App\Controllers\Api;

use App\Models\TourRepository;
use App\Models\BookingRepository;
use function json_response;
use function json_error;
use function json_input;
use function no_content;

final class TourApiController
{
    private TourRepository $tours;
    private BookingRepository $bookings;

    public function __construct()
    {
        $this->tours = new TourRepository();
        $this->bookings = new BookingRepository();
    }

    public function index(): void
    {
        json_response($this->tours->all());
    }

    public function show(string $id): void
    {
        $tour = $this->tours->find((int) $id);
        if ($tour === null) {
            json_error('Тур не найден', 404);
        }
        json_response($tour);
    }

    public function bookings(string $id): void
    {
        $tour = $this->tours->find((int) $id);
        if ($tour === null) {
            json_error('Тур не найден', 404);
        }
        $list = array_values(array_filter(
            $this->bookings->all(),
            fn($b) => (int) $b['tour_id'] === (int) $id
        ));
        json_response($list);
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $clean = $this->clean($data);
        $created = $this->tours->add($clean);

        header('Location: /api/tours/' . $created['id'], true, 201);
        json_response($created, 201);
    }

    public function update(string $id): void
    {
        $tour = $this->tours->find((int) $id);
        if ($tour === null) {
            json_error('Тур не найден', 404);
        }

        $data = json_input();
        $merged = array_merge($tour, $this->clean($data));

        $errors = $this->validate($merged);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $updated = $this->tours->update((int) $id, $merged);
        json_response($updated);
    }

    public function destroy(string $id): void
    {
        $tour = $this->tours->find((int) $id);
        if ($tour === null) {
            json_error('Тур не найден', 404);
        }

        $hasBookings = array_filter(
            $this->bookings->all(),
            fn($b) => (int) $b['tour_id'] === (int) $id
        );
        if (count($hasBookings) > 0) {
            json_error('Нельзя удалить тур: есть связанные бронирования', 409);
        }

        $this->tours->delete((int) $id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $title = $data['title'] ?? '';
        if (!is_string($title) || mb_strlen(trim($title)) < 3) {
            $errors['title'] = 'Название — не короче 3 символов';
        }
        $price = $data['price'] ?? null;
        if (!is_numeric($price) || $price <= 0) {
            $errors['price'] = 'Цена должна быть положительным числом';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        $allowed = ['title', 'country', 'price'];
        return array_intersect_key($data, array_flip($allowed));
    }
}