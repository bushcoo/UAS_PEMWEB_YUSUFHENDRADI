CREATE DATABASE todo_app;

USE todo_app;

CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    status ENUM('Belum Selesai', 'Selesai') DEFAULT 'Belum Selesai'
);

INSERT INTO todos (judul, deskripsi, status) VALUES
('Belajar PHP', 'Mempelajari dasar-dasar PHP dan MySQL', 'Belum Selesai'),
('Kerjakan PR', 'Tugas matematika halaman 25', 'Selesai'),
('Belanja Mingguan', 'Beli sayur, buah, dan kebutuhan rumah tangga', 'Belum Selesai'),
('Latihan CSS', 'Membuat layout responsive dengan Bootstrap', 'Selesai'),
('Baca Buku', 'Baca bab 3 dari buku algoritma', 'Belum Selesai');