CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100) UNIQUE
);
CREATE TABLE tours (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    country VARCHAR(100) NOT NULL,
    price NUMERIC(12, 2) NOT NULL CHECK (price > 0)
);
CREATE TABLE bookings (
    id SERIAL PRIMARY KEY,
    client_id INTEGER NOT NULL REFERENCES clients(id) ON DELETE CASCADE,
    tour_id INTEGER NOT NULL REFERENCES tours(id) ON DELETE RESTRICT,
    title VARCHAR(200) NOT NULL,
    status VARCHAR(20) DEFAULT 'new' CHECK (status IN ('new', 'confirmed', 'paid', 'cancelled'))
);
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    booking_id INTEGER NOT NULL REFERENCES bookings(id) ON DELETE CASCADE,
    amount NUMERIC(12, 2) NOT NULL
);