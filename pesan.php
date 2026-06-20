<?php
require_once 'config/koneksi.php';

// tandai pesan sudah dibaca saat dibuka
if (isset($_GET['baca'])) {
    $id = (int) $_GET['baca'];
    $stmt = $conn->prepare("UPDATE messages SET dibaca = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: pesan.php");
    exit;
}

// hapus pesan
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: pesan.php");
    exit;
}

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
$totalBelumDibaca = $conn->query("SELECT COUNT(*) as total FROM messages WHERE dibaca = 0")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Masuk - Portofolio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Portofolio</h1>
        <nav><a href="index.php" class="back-link">← Kembali</a></nav>
    </header>

    <main>
        <section class="inbox-page">
            <h2>Pesan Masuk <?php if ($totalBelumDibaca > 0): ?><span class="badge"><?= $totalBelumDibaca ?> baru</span><?php endif; ?></h2>

            <div class="inbox-list">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="inbox-item <?= $row['dibaca'] ? '' : 'unread' ?>">
                            <div class="inbox-meta">
                                <strong><?= htmlspecialchars($row['nama']) ?></strong>
                                <span class="inbox-email"><?= htmlspecialchars($row['email']) ?></span>
                                <span class="inbox-date"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></span>
                            </div>
                            <p class="inbox-text"><?= nl2br(htmlspecialchars($row['pesan'])) ?></p>
                            <div class="inbox-actions">
                                <?php if (!$row['dibaca']): ?>
                                    <a href="pesan.php?baca=<?= $row['id'] ?>" class="btn-edit">Tandai Dibaca</a>
                                <?php endif; ?>
                                <a href="pesan.php?hapus=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Hapus pesan ini?')">Hapus</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="empty-msg">Belum ada pesan masuk.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Aditya. All Right Reserved</p>
    </footer>
</body>
</html>
<?php $conn->close(); ?>
