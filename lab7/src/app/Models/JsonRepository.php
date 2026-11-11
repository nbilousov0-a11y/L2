<?php
namespace App\Models;

abstract class JsonRepository
{
    protected string $name;
    protected string $file;

    public function __construct()
    {
        // Путь к папке storage
        $this->file = dirname(__DIR__, 2) . '/storage/' . $this->name . '.json';
    }

    protected function load(): array
    {
        if (!file_exists($this->file)) return [];
        return json_decode(file_get_contents($this->file), true) ?: [];
    }

    protected function save(array $data): void
    {
        file_put_contents($this->file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
        foreach ($all as $item) {
            if ((int) $item['id'] > $maxId) $maxId = (int) $item['id'];
        }
        $data['id'] = $maxId + 1;
        $all[] = $data;
        $this->save($all);
        return $data;
    }

    public function update(int $id, array $data): ?array
    {
        $all = $this->load();
        foreach ($all as $i => $item) {
            if ((int) $item['id'] === $id) {
                $data['id'] = $id;
                $all[$i] = $data;
                $this->save($all);
                return $data;
            }
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $all = $this->load();
        foreach ($all as $i => $item) {
            if ((int) $item['id'] === $id) {
                array_splice($all, $i, 1);
                $this->save($all);
                return true;
            }
        }
        return false;
    }
}