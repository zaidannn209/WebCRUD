<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id=$id");
$d = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $kegiatan = $_POST['kegiatan'];
    $tanggal  = $_POST['tanggal'];
    mysqli_query($koneksi, "UPDATE catatan SET kegiatan='$kegiatan', tanggal='$tanggal' WHERE id=$id");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Data</title></head>
<body>
    <h3>Edit Catatan</h3>
    <form method="POST" action="">
        <input type="text" name="kegiatan" value="<?php echo $d['kegiatan']; ?>" required>
        <input type="date" name="tanggal" value="<?php echo $d['tanggal']; ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
