-- Анализ до индекса
EXPLAIN ANALYZE SELECT * FROM bookings WHERE client_id = 1;

-- Создание индекса
CREATE INDEX idx_bookings_client ON bookings(client_id);

-- Анализ после индекса
EXPLAIN ANALYZE SELECT * FROM bookings WHERE client_id = 1;