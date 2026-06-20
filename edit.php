<?php
require_once 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

// proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $teknologi = trim($_POST['teknologi']);
    $link_demo = trim($_POST['link_demo']);

    // cek apakah ada gambar baru diupload
    $stmtCek = $conn->prepare("SELECT gambar FROM projects WHERE id = ?");
    $stmtCek->bind_param("i", $id);
    $stmtCek->execute();
    $dataLama = $stmtCek->get_result()->fetch_assoc();
    $gambar = $dataLama['gambar'];
    $stmtCek->close();

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array(strtolower($ext), $allowed)) {
            // hapus gambar lama jika ada
            if ($gambar && file_exists('uploads/' . $gambar)) {
                unlink('uploads/' . $gambar);
            }
            $gambar = uniqid('proj_') . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
        }
    }

    $stmt = $conn->prepare("UPDATE projects SET judul=?, deskripsi=?, teknologi=?, link_demo=?, gambar=? WHERE id=?");
    $stmt->bind_param("sssssi", $judul, $deskripsi, $teknologi, $link_demo, $gambar, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php?pesan=edit");
    exit;
}

// ambil data project yang mau diedit
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project - Portofolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Portofolio</h1>
        <nav><a href="index.php" class="back-link">← Kembali</a></nav>
    </header>

    <main>
        <section class="form-page edit-page">
            <h2>Edit Project</h2>

            <form action="edit.php?id=<?= $project['id'] ?>" method="POST" enctype="multipart/form-data" class="edit-grid">
                <div class="edit-col-main">
                    <div class="form-group">
                        <label for="judul">Judul Project:</label>
                        <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($project['judul']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi:</label>
                        <textarea id="deskripsi" name="deskripsi" rows="6" required><?= htmlspecialchars($project['deskripsi']) ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="teknologi">Teknologi yang Digunakan:</label>
                            <input type="text" id="teknologi" name="teknologi" value="<?= htmlspecialchars($project['teknologi']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="link_demo">Link Demo / GitHub (opsional):</label>
                            <input type="url" id="link_demo" name="link_demo" value="<?= htmlspecialchars($project['link_demo'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="edit-col-side">
                    <?php if (!empty($project['gambar']) && file_exists('uploads/' . $project['gambar'])): ?>
                        <div class="form-group">
                            <label>Gambar Saat Ini:</label>
                            <img src="uploads/<?= htmlspecialchars($project['gambar']) ?>" class="preview-img-large" alt="">
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label>Gambar Saat Ini:</label>
                            <div class="preview-img-large preview-empty">Belum ada gambar</div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="gambar">Ganti Gambar (opsional):</label>
                        <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <button type="submit">Update Project</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Aditya. All Right Reserved</p>
    </footer>
</body>
</html>
