<?php
// app/Models/BookingRepository.php — модель бронирований.

declare(strict_types=1);

namespace App\Models;

final class BookingRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = STORAGE_PATH . '/bookings.json';
    }

    private function load(): array
    {
        if (!file_exists($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?: [];
    }

    private function save(array $data): void
    {
        file_put_contents(
            $this->file,
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    public function all(): array
    {
        return $this->load();
    }

    public function find(int $id): ?array
    {
        foreach ($this->load() as $item) {
            if ((int) $item['id'] === $id) return $item;
        }
        return null;
    }

    public function add(array $data): array
    {
        $all = $this->load();
        $maxId = 0;
        foreach ($all as $b) {
            if ((int) $b['id'] > $maxId) $maxId = (int) $b['id'];
        }
        $data['id'] = $maxId + 1;
        $all[] = $data;
        $this->save($all);
        return $data;
    }
}