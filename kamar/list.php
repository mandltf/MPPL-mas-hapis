<?php
include '../config/database.php';

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

// Menggunakan $conn untuk query
$query = $conn->query("SELECT * FROM kamar");
$kamar = $query->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kamar</title>
    <link rel="stylesheet" href="../assets/style1.css">
</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="create.php"><i class="fas fa-bed"></i> Tambah Kamar Baru</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Daftar Kamar</h1>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nomor Kamar</th>
                        <th>Tipe Kamar</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kamar as $row): ?>
                        <tr>
                            <td><?= $row['id_kamar'] ?></td>
                            <td><?= $row['nomor_kamar'] ?></td>
                            <td><?= $row['tipe_kamar'] ?></td>
                            <td><?= $row['harga'] ?></td>
                            <td>
                                <a href="edit.php?id=<?= $row['id_kamar'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?= $row['id_kamar'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
