-- Проверка CHECK (должна быть ошибка, так как цена < 0)
INSERT INTO tours (title, country, price) VALUES ('Брак', 'Тест', -100);

-- Проверка FK (должна быть ошибка, так как нет тура с id 999)
INSERT INTO bookings (client_id, tour_id, title) VALUES (1, 999, 'Ошибка');

-- Проверка UNIQUE (должна быть ошибка, так как email уже есть)
INSERT INTO clients (name, email) VALUES ('Тест', 'ivanov@mail.ru');