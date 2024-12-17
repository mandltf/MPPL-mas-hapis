<?php
include('../config/database.php');  // Menghubungkan ke database
session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga = $_POST['harga'];

    $query = "INSERT INTO kamar (nomor_kamar, tipe_kamar, harga) VALUES ('$nomor_kamar', '$tipe_kamar', '$harga')";
    if ($conn->query($query) === TRUE) {
        header('Location: list.php');  // Redirect ke halaman list setelah berhasil
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar</title>
    <!-- Menggunakan CDN Bootstrap untuk tampilan yang lebih menarik -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container my-5">
    <h1 class="mb-4 text-center">Tambah Kamar</h1>

    <!-- Form Tambah Kamar -->
    <form action="create.php" method="POST">
        <div class="mb-3">
            <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
            <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
            <select name="tipe_kamar" id="tipe_kamar" class="form-select" required>
                <option value="Biasa">Biasa</option>
                <option value="Eksekutif">Eksekutif</option>
                <option value="Luxury">Luxury</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" id="harga" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="list.php" class="btn btn-secondary ms-2">Kembali</a>
    </form>
</div>

<!-- JavaScript Bootstrap CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

<?php $conn->close(); ?>
