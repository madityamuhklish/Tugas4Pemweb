<?php
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pesan = trim($_POST['message']);

    $stmt = $conn->prepare("INSERT INTO messages (nama, email, pesan) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $pesan);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php?pesan=kontak#contact");
    exit;
}

header("Location: index.php");
exit;
