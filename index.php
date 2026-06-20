<?php
require_once 'config/koneksi.php';

// ambil semua data project, terbaru di atas
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");

// tampilkan pesan sukses/gagal dari proses sebelumnya (lewat URL)
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';

// cari foto profil otomatis, apapun nama file & ekstensinya
$fotoProfil = null;
foreach (glob('foto*.{jpg,jpeg,png,JPG,JPEG,PNG,webp,WEBP}', GLOB_BRACE) as $f) {
    $fotoProfil = $f;
    break;
}

// hitung pesan belum dibaca untuk badge notifikasi
$totalBelumDibaca = $conn->query("SELECT COUNT(*) as total FROM messages WHERE dibaca = 0")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio Mahasiswa TI UIR</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Portofolio</h1>
        <nav>
            <ul>
                <li><a href="#about">About</a></li>
                <li><a href="#education">Education</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="pesan.php" class="nav-inbox">Pesan <?php if ($totalBelumDibaca > 0): ?><span class="nav-badge"><?= $totalBelumDibaca ?></span><?php endif; ?></a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="hero">
            <?php if ($fotoProfil): ?>
                <img src="<?= htmlspecialchars($fotoProfil) ?>" width="200" height="200" alt="Foto Profil">
            <?php else: ?>
                <div class="hero-avatar-fallback">MA</div>
            <?php endif; ?>
            <div class="hero-text">
                <h2>M.Aditya Mukhlish</h2>
                <p>Halo, saya adalah seorang mahasiswa Teknik Informatika</p>
                <p>Data Analyst | Informatics Engineering Student at UIR</p>
            </div>
        </section>

        <article id="about">
            <h2>Tentang Saya</h2>
            <p>Saya adalah seorang mahasiswa yang memiliki minat dan fokus pada bidang Data Analyst. Saya tertarik untuk mengolah, menganalisis, dan menginterpretasikan data guna menghasilkan informasi yang bermanfaat dalam pengambilan keputusan. Saya terus mengembangkan kemampuan dalam statistik, visualisasi data, machine learning dasar, serta penggunaan berbagai tools seperti Python, SQL, dan Excel. Sebagai mahasiswa, saya berkomitmen untuk belajar secara berkelanjutan dan menerapkan ilmu analisis data dalam menyelesaikan berbagai permasalahan secara efektif dan berbasis data.</p>
        </article>

        <section id="education">
            <h2>Riwayat Pendidikan</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Institusi</th>
                        <th>Jurusan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2024 - sekarang</td>
                        <td>Universitas Islam Riau</td>
                        <td>Teknik Informatika</td>
                    </tr>
                    <tr>
                        <td>2021-2024</td>
                        <td>SMA Negeri 1 Lirik</td>
                        <td>MIA</td>
                    </tr>
                    <tr>
                        <td>2018 - 2021</td>
                        <td>SMP Negeri 3 Pasir Penyu</td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <td>2012 - 2018</td>
                        <td>SD Negeri 007 Sidomulyo</td>
                        <td> - </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- ============ SECTION CRUD PROJECTS ============ -->
        <section id="projects">
            <h2>Daftar Project / Karya</h2>

            <?php if ($pesan === 'tambah'): ?>
                <div class="alert alert-success">Project berhasil ditambahkan.</div>
            <?php elseif ($pesan === 'edit'): ?>
                <div class="alert alert-success">Project berhasil diperbarui.</div>
            <?php elseif ($pesan === 'hapus'): ?>
                <div class="alert alert-success">Project berhasil dihapus.</div>
            <?php endif; ?>

            <a href="tambah.php" class="btn-add">+ Tambah Project Baru</a>

            <div class="project-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="project-card">
                            <?php if (!empty($row['gambar']) && file_exists('uploads/' . $row['gambar'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>">
                            <?php else: ?>
                                <div class="project-placeholder">No Image</div>
                            <?php endif; ?>

                            <div class="project-body">
                                <h3><?= htmlspecialchars($row['judul']) ?></h3>
                                <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
                                <span class="tech-tag"><?= htmlspecialchars($row['teknologi']) ?></span>

                                <?php if (!empty($row['link_demo'])): ?>
                                    <a href="<?= htmlspecialchars($row['link_demo']) ?>" target="_blank" class="project-link">Lihat Demo →</a>
                                <?php endif; ?>

                                <div class="project-actions">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus project ini?')">Hapus</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="empty-msg">Belum ada project. Yuk tambahkan yang pertama!</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="contact">
            <h2>Hubungi Saya</h2>

            <?php if ($pesan === 'kontak'): ?>
                <div class="alert alert-success">Pesan kamu berhasil dikirim. Terima kasih!</div>
            <?php endif; ?>

            <form action="kirim_pesan.php" method="POST">
                <div class="form-group">
                    <label for="name">Nama Lengkap:</label>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="contoh@email.com" required>
                </div>
                <div class="form-group">
                    <label for="message">Pesan:</label>
                    <textarea id="message" name="message" rows="4" placeholder="Tulis pesan Anda di sini" required></textarea>
                </div>
                <button type="submit">Kirim Pesan</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Aditya. All Right Reserved</p>
    </footer>

    <script src="todo.js"></script>
</body>
</html>
<?php $conn->close(); ?>
