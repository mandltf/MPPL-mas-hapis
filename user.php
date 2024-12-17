<?php
include('config/database.php');  // Menghubungkan ke database

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

// Mengambil daftar kamar
$query = "SELECT * FROM kamar";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kamar</title>
    <link rel="stylesheet" href="../assets/style1.css">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;  /* Membungkus elemen ke baris berikutnya jika tidak muat */
            gap: 20px;  /* Jarak antar kartu */
        }
        .card {
            width: 48%;  /* Mengatur lebar setiap kartu untuk dua kartu per baris */
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="../transaksi/list.php"><i class="fas fa-file-invoice"></i> Daftar Transaksi</a>
            <a href="../penyewa/list.php"><i class="fas fa-users"></i> Daftar Penyewa</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Daftar Kamar</h1>
            </div>
            <br>
            <div class="card-container">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-success">Nomor Kamar: <?= $row['nomor_kamar'] ?></h5>
                            <p class="card-text">
                                <strong>Tipe:</strong> <?= $row['tipe_kamar'] ?><br>
                                <strong>Harga:</strong> Rp <?= number_format($row['harga'], 0, ',', '.') ?><br>
                            </p>
                            <p>
                                <span class="badge <?= $row['status'] === 'Terisi' ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $row['status'] === 'Terisi' ? 'Tidak Tersedia' : 'Tersedia' ?>
                                </span>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="d-flex justify-content-between container-btn">
                <a href="../transaksi/create.php" class="btn btn-success">Tambah Transaksi Baru</a>
                <a href="../transaksi/list.php" class="btn btn-secondary">Daftar Transaksi</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>
