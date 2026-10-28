<?php
namespace App\Controllers\Api;

use App\Models\ClientRepository;
use function json_response;
use function json_error;
use function json_input;
use function no_content;

final class ClientApiController
{
    private ClientRepository $clients;

    public function __construct()
    {
        $this->clients = new ClientRepository();
    }

    public function index(): void
    {
        json_response($this->clients->all());
    }

    public function show(string $id): void
    {
        $client = $this->clients->find((int) $id);
        if ($client === null) {
            json_error('Клиент не найден', 404);
        }
        json_response($client);
    }

    public function store(): void
    {
        $data = json_input();
        $errors = $this->validate($data);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $clean = $this->clean($data);
        $created = $this->clients->add($clean);

        header('Location: /api/clients/' . $created['id'], true, 201);
        json_response($created, 201);
    }

    public function update(string $id): void
    {
        $client = $this->clients->find((int) $id);
        if ($client === null) {
            json_error('Клиент не найден', 404);
        }

        $data = json_input();
        $merged = array_merge($client, $this->clean($data));

        $errors = $this->validate($merged);
        if ($errors) {
            json_error('Ошибка проверки данных', 422, $errors);
        }

        $updated = $this->clients->update((int) $id, $merged);
        json_response($updated);
    }

    public function destroy(string $id): void
    {
        $client = $this->clients->find((int) $id);
        if ($client === null) {
            json_error('Клиент не найден', 404);
        }
        $this->clients->delete((int) $id);
        no_content();
    }

    private function validate(array $data): array
    {
        $errors = [];
        $name = $data['name'] ?? '';
        if (!is_string($name) || mb_strlen(trim($name)) < 3) {
            $errors['name'] = 'Имя — не короче 3 символов';
        }
        return $errors;
    }

    private function clean(array $data): array
    {
        $allowed = ['name', 'phone', 'email'];
        return array_intersect_key($data, array_flip($allowed));
    }
}