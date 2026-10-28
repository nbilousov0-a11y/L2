-- 1. CREATE (Создать)
INSERT INTO bookings (client_id, tour_id, title, status) 
VALUES (1, 2, 'Новая поездка в Турцию', 'new');

-- 2. READ (Прочитать с JOIN)
SELECT b.id, c.name AS client_name, t.title AS tour_title, b.status 
FROM bookings b
JOIN clients c ON b.client_id = c.id
JOIN tours t ON b.tour_id = t.id;

-- 3. UPDATE (Обновить)
UPDATE bookings SET status = 'paid' WHERE id = 5;

-- 4. DELETE (Удалить)
DELETE FROM bookings WHERE status = 'cancelled';