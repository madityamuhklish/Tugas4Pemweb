<?php
require_once 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// hapus file gambar terkait (jika ada) sebelum hapus data
$stmt = $conn->prepare("SELECT gambar FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row && !empty($row['gambar']) && file_exists('uploads/' . $row['gambar'])) {
    unlink('uploads/' . $row['gambar']);
}

// hapus data dari database
$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: index.php?pesan=hapus");
exit;
