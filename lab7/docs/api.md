# Документация REST API «Туристическое агентство»

Все запросы и ответы передаются в формате JSON.

## Ресурс: Бронирования (/api/bookings)

### GET /api/bookings
Возвращает список всех бронирований.
**Ответ 200 OK:**
```json
[{"id": 1, "tour_id": 1, "title": "Египет", "status": "confirmed"}]
GET /api/bookings/{id}
Возвращает одно бронирование. Если не найдено — 404.

POST /api/bookings
Создает новое бронирование.
Тело запроса:

json
{"title": "Тур в Сочи", "tour_id": 1, "status": "new"}
Ответ 201 Created: JSON с новой записью.
Ошибки: 400 (неверный JSON), 422 (данные не прошли валидацию).

PATCH /api/bookings/{id}
Обновляет поля бронирования. Возвращает 200 OK.

DELETE /api/bookings/{id}
Удаляет бронирование. Возвращает 204 No Content.

Ресурс: Туры (/api/tours)
GET /api/tours — список туров

GET /api/tours/{id} — один тур

GET /api/tours/{id}/bookings — бронирования конкретного тура

POST /api/tours — создать тур

PATCH /api/tours/{id} — обновить тур

DELETE /api/tours/{id} — удалить тур (возвращает 409, если есть связанные бронирования)

Ресурс: Клиенты (/api/clients)
GET /api/clients — список клиентов

GET /api/clients/{id} — один клиент

POST /api/clients — создать клиента

PATCH /api/clients/{id} — обновить клиента

DELETE /api/clients/{id} — удалить клиента