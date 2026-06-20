<?php
// ====================================================
// KONEKSI DATABASE
// Sesuaikan username/password dengan setting MySQL kamu
// (default XAMPP/Laragon: user 'root', password kosong)
// ====================================================

$host     = "localhost";
$user     = "root";
$password = "";
$database = "portofolio_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
