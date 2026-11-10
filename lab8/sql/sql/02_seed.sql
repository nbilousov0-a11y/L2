INSERT INTO clients (name, phone, email) VALUES 
('Иванов Иван', '+79001112233', 'ivanov@mail.ru'),
('Петрова Анна', '+79004445566', 'petrova@mail.ru'),
('Сидоров Петр', '+79007778899', 'sidorov@mail.ru');

INSERT INTO tours (title, country, price) VALUES 
('Египет', 'Египет', 80000.00),
('Турция', 'Турция', 95000.00),
('Италия', 'Италия', 120000.00);

INSERT INTO bookings (client_id, tour_id, title, status) VALUES 
(1, 1, 'Поездка в Египет', 'confirmed'),
(2, 2, 'Отдых в Турции', 'paid');