<?php
$host = "localhost";
$user = "root";
$pass = "kali"; // Jika MariaDB kamu ada password-nya, isi di dalam tanda petik ini (contoh: "kali")
$db   = "db_kegiatan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>
