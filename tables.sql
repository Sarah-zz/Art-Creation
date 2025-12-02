CREATE DATABASE artcreation_db;

USE artcreation_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    pseudo VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role INT NOT NULL DEFAULT 1 -- 1 = user, 2 = admin
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    size VARCHAR(50)
);

CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    level VARCHAR(50),
    description TEXT,
    date DATETIME NOT NULL,
    max_places INT NOT NULL DEFAULT 10,
    duration VARCHAR(20) NOT NULL DEFAULT '3h'
);

CREATE TABLE favorites (
    user_id INT NOT NULL,
    gallery_id INT NOT NULL,
    PRIMARY KEY (user_id, gallery_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gallery_id) REFERENCES gallery(id) ON DELETE CASCADE
);

CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workshop_id INT NOT NULL,
    participants INT NOT NULL CHECK (participants BETWEEN 1 AND 3),
    registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (workshop_id) REFERENCES workshops(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (user_id, workshop_id)
);