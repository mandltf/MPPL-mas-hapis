<?php
include('../config/database.php');
session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

// Cek apakah pengguna adalah admin
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';

// Query untuk daftar transaksi
if ($is_admin) {
    // Jika admin, tampilkan semua transaksi
    $query = "SELECT t.id_transaksi, p.username, m.tanggal_pesan, t.total_pembayaran 
              FROM transaksi t
              JOIN memesan m ON t.id_pesanan = m.id_pesanan
              JOIN users p ON m.id_user = p.id_user";
} else {
    // Jika bukan admin, tampilkan hanya transaksi milik pengguna yang login
    $username = $_SESSION['username']; // Ambil username dari session
    $query = "SELECT t.id_transaksi, p.username, m.tanggal_pesan, t.total_pembayaran 
              FROM transaksi t
              JOIN memesan m ON t.id_pesanan = m.id_pesanan
              JOIN users p ON m.id_user = p.id_user
              WHERE p.username = '$username'";  // Hanya transaksi milik user yang login
}

$result = $conn->query($query);

// Debug: Cek apakah ada hasil
if ($result === false) {
    echo "Error: " . $conn->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <link rel="stylesheet" href="../assets/style1.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <?php if ($is_admin): ?>
                <a href="create.php"><i class="fas fa-plus"></i> Tambah Transaksi</a>
            <?php endif; ?>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Daftar Transaksi</h1>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Nama Penyewa</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Total Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $row['id_transaksi']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['tanggal_pesan'])); ?></td>
                                <td><?php echo "Rp " . number_format($row['total_pembayaran'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="detail.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-success btn-sm">Detail</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
