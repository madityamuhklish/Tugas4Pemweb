<?php
require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $teknologi = trim($_POST['teknologi']);
    $link_demo = trim($_POST['link_demo']);
    $gambar    = null;

    // proses upload gambar (opsional)
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array(strtolower($ext), $allowed)) {
            $gambar = uniqid('proj_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
        }
    }

    // simpan ke database pakai prepared statement (aman dari SQL Injection)
    $stmt = $conn->prepare("INSERT INTO projects (judul, deskripsi, teknologi, link_demo, gambar) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $judul, $deskripsi, $teknologi, $link_demo, $gambar);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php?pesan=tambah");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Project - Portofolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Portofolio</h1>
        <nav><a href="index.php" class="back-link">← Kembali</a></nav>
    </header>

    <main>
        <section class="form-page edit-page">
            <h2>Tambah Project Baru</h2>

            <form action="tambah.php" method="POST" enctype="multipart/form-data" class="edit-grid">
                <div class="edit-col-main">
                    <div class="form-group">
                        <label for="judul">Judul Project:</label>
                        <input type="text" id="judul" name="judul" placeholder="Contoh: Sistem Informasi Bansos" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="6" placeholder="Jelaskan tentang project ini..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="teknologi">Teknologi yang Digunakan:</label>
                            <input type="text" id="teknologi" name="teknologi" placeholder="Contoh: PHP, MySQL, Bootstrap" required>
                        </div>

                        <div class="form-group">
                            <label for="link_demo">Link Demo / GitHub (opsional):</label>
                            <input type="url" id="link_demo" name="link_demo" placeholder="https://github.com/...">
                        </div>
                    </div>
                </div>

                <div class="edit-col-side">
                    <div class="form-group">
                        <label>Preview Gambar:</label>
                        <div class="preview-img-large preview-empty" id="imgPreview">Belum ada gambar dipilih</div>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar Project (opsional):</label>
                        <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp" onchange="previewGambar(this)">
                    </div>

                    <button type="submit">Simpan Project</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        function previewGambar(input) {
            const preview = document.getElementById('imgPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.outerHTML = `<img src="${e.target.result}" class="preview-img-large" id="imgPreview">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <footer>
        <p>&copy; 2026 Aditya. All Right Reserved</p>
    </footer>
</body>
</html>
