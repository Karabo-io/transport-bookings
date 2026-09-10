
CREATE DATABASE IF NOT EXISTS transport_company;

USE transport_company;

DROP TABLE IF EXISTS bookings;

CREATE TABLE bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    passenger_name VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    fare DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO bookings (passenger_name, destination, fare) VALUES
('Alice Nkosi', 'Cape Town', 450.00),
('Thabo Mokoena', 'Durban', 650.00),
('Chantelle Smith', 'Pretoria', 350.00),
('David Molefe', 'Cape Town', 550.00),
('Lerato Dlamini', 'Johannesburg', 300.00),
('Mpho Khumalo', 'Durban', 700.00);
