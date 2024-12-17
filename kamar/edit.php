<?php
include '../config/database.php'; // Koneksi ke database
session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangkap data dari form
    $id = $_POST['id'];  // id kamar yang ingin diedit
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga = $_POST['harga'];

    // Query UPDATE untuk memperbarui data kamar
    $stmt = $conn->prepare("UPDATE kamar SET nomor_kamar = ?, tipe_kamar = ?, harga = ? WHERE id_kamar = ?");
    $stmt->bind_param("ssdi", $nomor_kamar, $tipe_kamar, $harga, $id); // Tipe data: string, string, integer, integer
    $stmt->execute();

    // Redirect setelah update berhasil
    header("Location: list.php");
    exit();
}

// Ambil data kamar berdasarkan ID yang dikirim melalui URL
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM kamar WHERE id_kamar = ?");
$stmt->bind_param("i", $id); // Tipe data integer untuk ID
$stmt->execute();
$result = $stmt->get_result();
$kamar = $result->fetch_assoc(); // Ambil hasil sebagai array asosiatif
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamar</title>
    <!-- Menggunakan CDN Bootstrap untuk tampilan yang lebih menarik -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css"> <!-- Link ke style.css jika ada -->
</head>
<body>

<div class="container my-5">
    <h1 class="mb-4 text-center">Edit Kamar</h1>

    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $kamar['id_kamar'] ?>"> <!-- Hidden input untuk ID kamar -->
        
        <!-- Form Nomor Kamar -->
        <div class="mb-3">
            <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" id="nomor_kamar" name="nomor_kamar" value="<?= $kamar['nomor_kamar'] ?>" required>
        </div>
        
        <!-- Form Tipe Kamar -->
        <div class="mb-3">
            <label for="tipe_kamar" class="form-label">Tipe Kamar</label>
            <select class="form-select" id="tipe_kamar" name="tipe_kamar" required>
                <option value="Biasa" <?= $kamar['tipe_kamar'] == 'Biasa' ? 'selected' : '' ?>>Biasa</option>
                <option value="Eksekutif" <?= $kamar['tipe_kamar'] == 'Eksekutif' ? 'selected' : '' ?>>Eksekutif</option>
                <option value="Luxury" <?= $kamar['tipe_kamar'] == 'Luxury' ? 'selected' : '' ?>>Luxury</option>
            </select>
        </div>
        
        <!-- Form Harga -->
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" value="<?= $kamar['harga'] ?>" required>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="list.php" class="btn btn-secondary ms-2">Kembali ke Daftar Kamar</a>
    </form>
</div>

<!-- JavaScript Bootstrap CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

<?php $conn->close(); ?>
