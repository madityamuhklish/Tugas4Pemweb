CREATE DATABASE IF NOT EXISTS portofolio_db;
USE portofolio_db;

DROP TABLE IF EXISTS projects;

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    teknologi VARCHAR(150) NOT NULL,
    link_demo VARCHAR(255) DEFAULT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS messages;

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pesan TEXT NOT NULL,
    dibaca TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO projects (judul, deskripsi, teknologi, link_demo) VALUES
('Sistem Informasi Monitor Bansos', 'Aplikasi web untuk memonitor penyaluran bantuan sosial di tingkat kelurahan.', 'PHP, MySQL, Bootstrap', ''),
('Tournament Management SIOMAS', 'Sistem manajemen turnamen esports dengan fitur multi-role.', 'HTML, CSS, JavaScript', '');
