<?php
session_start();
include 'koneksi.php';

// Cek koneksi ke database
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika sudah login, lempar ke index.php
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
$username = trim($_POST['username']);
$password = trim($_POST['password']);

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE TRIM(username)='$username' AND TRIM(password)='$password'");
    if ($query && mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - zaidanwebsite.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            background: #0d6efd;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .login-header i { font-size: 40px; margin-bottom: 10px; }
        .form-control { border-radius: 8px; padding: 12px 15px; }
        .btn-login { border-radius: 8px; padding: 12px; font-weight: bold; background: #0d6efd; border: none; }
        .btn-login:hover { background: #0b5ed7; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <i class="fa-solid fa-user-shield"></i>
            <h4 class="fw-bold mb-0">zaidanwebsite.com</h4>
            <small class="text-white-50">Silakan login untuk mengakses sistem</small>
        </div>

        <div class="p-4">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center py-2 fs-6" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> <?= $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-user text-muted"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100 btn-login shadow-sm">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Sekarang
                </button>
            </form>
        </div>

        <div class="card-footer bg-light text-center py-3 text-muted small border-0">
            &copy; 2026 Zaidan Ataya Rizqulah &bull; XI TKJ 4
        </div>
    </div>

</body>
</html>
