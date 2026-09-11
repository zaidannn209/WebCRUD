<?php
session_start();

// Validasi Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

include 'koneksi.php';

// Ambil data identitas
$query_bio = mysqli_query($koneksi, "SELECT * FROM biodata LIMIT 1");
$bio = mysqli_fetch_assoc($query_bio);

// Proses Tambah Data (Khusus Admin)
if (isset($_POST['tambah']) && $role === 'admin') {
    $kegiatan = $_POST['kegiatan'];
    $tanggal = $_POST['tanggal'];

    $nama_file = $_FILES['file']['name'];
    $tmp_file = $_FILES['file']['tmp_name'];

    if (!empty($nama_file)) {
        $file_baru = time() . '_' . $nama_file;
        move_uploaded_file($tmp_file, 'uploads/' . $file_baru);
    } else {
        $file_baru = null;
    }

    mysqli_query($koneksi, "INSERT INTO catatan (kegiatan, tanggal, file) VALUES ('$kegiatan', '$tanggal', '$file_baru')");
    header("Location: index.php");
    exit;
}

// Proses Hapus Data (Khusus Admin)
if (isset($_GET['hapus']) && $role === 'admin') {
    $id = $_GET['hapus'];

    $get_file = mysqli_query($koneksi, "SELECT file FROM catatan WHERE id=$id");
    $data_file = mysqli_fetch_assoc($get_file);
    if (!empty($data_file['file']) && file_exists("uploads/" . $data_file['file'])) {
        unlink("uploads/" . $data_file['file']);
    }

    mysqli_query($koneksi, "DELETE FROM catatan WHERE id=$id");
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Catatan Kegiatan - zaidanwebsite.com</title>
    <!-- Bootstrap 5 CSS & FontAwesome Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 12px; }
        .btn { border-radius: 8px; }
        .table img { border-radius: 8px; object-fit: cover; }
    </style>
</head>
<body>

    <!-- Navbar Header Modern -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid fa-layer-group me-2"></i>zaidanwebsite.com</a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="fa-solid fa-circle-user me-1"></i> <b><?= $username; ?></b> 
                    <span class="badge bg-<?= $role === 'admin' ? 'danger' : 'info'; ?> ms-1"><?= strtoupper($role); ?></span>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <!-- Card Identitas Pemilik Server -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title text-primary fw-bold mb-3"><i class="fa-solid fa-server me-2"></i>Identitas Pemilik Server</h5>
                <div class="row text-center text-md-start g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block">NAMA PEMILIK</small>
                            <strong>Zaidan Ataya Rizqulah</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block">KELAS</small>
                            <strong>XI TKJ 4</strong>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <small class="text-muted d-block">NO. ABSEN</small>
                            <strong>34</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tambah Data (Khusus Admin) -->
        <?php if ($role === 'admin'): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="card-title text-success fw-bold mb-3"><i class="fa-solid fa-plus-circle me-2"></i>Tambah Catatan & Unggah Berkas</h5>
                <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label font-weight-bold">Nama Kegiatan</label>
                        <input type="text" name="kegiatan" class="form-control" required placeholder="Contoh: Konfigurasi DNS Server">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Berkas / Foto</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-12">
                        <button type="submit" name="tambah" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info shadow-sm d-flex align-items-center" role="alert">
            <i class="fa-solid fa-info-circle me-2 fs-4"></i>
            <div>Anda masuk sebagai <b>User (View-Only)</b>. Anda tidak memiliki akses untuk menambah, mengubah, atau menghapus data.</div>
        </div>
        <?php endif; ?>

        <!-- Tabel Daftar Catatan -->
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title text-dark fw-bold mb-3"><i class="fa-solid fa-list-check me-2"></i>Daftar Catatan Kegiatan</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th>KEGIATAN</th>
                                <th style="width: 130px;">TANGGAL</th>
                                <th style="width: 120px;">PREVIEW</th>
                                <th style="width: 120px;">BERKAS</th>
                                <?php if ($role === 'admin'): ?>
                                    <th style="width: 140px;" class="text-center">AKSI</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM catatan ORDER BY id DESC");
                            while ($d = mysqli_fetch_array($data)):
                                $file_path = "uploads/" . $d['file'];
                                $ext = strtolower(pathinfo($d['file'], PATHINFO_EXTENSION));
                                $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($d['kegiatan']); ?></strong></td>
                                <td><?= $d['tanggal']; ?></td>
                                <td>
                                    <?php if (!empty($d['file']) && $is_image && file_exists($file_path)): ?>
                                        <a href="<?= $file_path; ?>" target="_blank">
                                            <img src="<?= $file_path; ?>" width="60" height="60" alt="Preview">
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tidak Ada</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($d['file'])): ?>
                                        <a href="<?= $file_path; ?>" download class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-download"></i> Download</a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($role === 'admin'): ?>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $d['id']; ?>" class="btn btn-warning btn-sm text-white"><i class="fa-solid fa-pen"></i> Edit</a>
                                    <a href="index.php?hapus=<?= $d['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
