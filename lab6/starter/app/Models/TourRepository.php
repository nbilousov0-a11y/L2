<?php
// app/Models/TourRepository.php — модель туров.

declare(strict_types=1);

namespace App\Models;

final class TourRepository
{
    private string $file;

    public function __construct()
    {
        $this->file = STORAGE_PATH . '/tours.json';
    }

    private function load(): array
    {
        if (!file_exists($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?: [];
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
}