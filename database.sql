CREATE DATABASE IF NOT EXISTS gruzovozoff;
USE gruzovozoff;

-- Таблица пользователей
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    login VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица типов грузов
CREATE TABLE cargo_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Таблица заявок
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    cargo_type_id INT NOT NULL,
    delivery_date DATETIME NOT NULL,
    weight FLOAT NOT NULL,
    dimensions VARCHAR(255) NOT NULL,
    pickup_address TEXT NOT NULL,
    delivery_address TEXT NOT NULL,
    status ENUM('Новая', 'В работе', 'Отменена') DEFAULT 'Новая',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (cargo_type_id) REFERENCES cargo_types(id)
);

-- Таблица отзывов
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Вставка типов грузов
INSERT INTO cargo_types (name) VALUES 
('Хрупкое'),
('Скоропортящееся'),
('Требуется рефрижератор'),
('Животные'),
('Жидкость'),
('Мебель'),
('Мусор');

-- Создание администратора
INSERT INTO users (login, password, full_name, phone, email, is_admin) 
VALUES ('admin', '$2y$10$YourHashedPasswordHere', 'Администратор', '+7(000)-000-00-00', 'admin@gruzovozoff.ru', TRUE); 