<?php
include('../config/database.php');

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

// Cek apakah pengguna adalah admin (misalnya dengan mengecek level user di session)
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'Admin';

if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];
    
    // Query untuk mendapatkan detail transaksi
    $query = "SELECT t.id_transaksi, p.username, m.tanggal_pesan, k.nomor_kamar, u.durasi, t.total_pembayaran, t.status, u.id_kamar
              FROM transaksi t
              JOIN memesan m ON t.id_pesanan = m.id_pesanan
              JOIN users p ON m.id_user = p.id_user
              JOIN menggunakan u ON t.id_transaksi = u.id_transaksi
              JOIN kamar k ON u.id_kamar = k.id_kamar
              WHERE t.id_transaksi = $id_transaksi";
    
    // Eksekusi query dan cek apakah berhasil
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Jika data tidak ditemukan, set $row ke array kosong atau tampilkan pesan error
        $row = [];
        $error_message = "Data transaksi tidak ditemukan.";
    }
} else {
    // Jika parameter id tidak ada, set $row ke array kosong
    $row = [];
    $error_message = "ID transaksi tidak diberikan.";
}

// Proses perubahan status (untuk admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin && isset($row['id_transaksi'])) {
    if (isset($_POST['approve'])) {
        // Jika tombol approve ditekan, ubah status transaksi menjadi "Paid"
        $update_query = "UPDATE transaksi SET status = 'Paid' WHERE id_transaksi = $id_transaksi";
        if ($conn->query($update_query)) {
            // Update status kamar menjadi "Terisi" setelah status transaksi "Paid"
            $update_kamar_query = "UPDATE kamar SET status = 'Terisi' WHERE id_kamar = " . $row['id_kamar'];
            if ($conn->query($update_kamar_query)) {
                $row['status'] = 'Paid';  // Update status di halaman
                $success_message = "Status transaksi berhasil diperbarui menjadi Paid dan kamar telah diupdate menjadi Terisi.";
            } else {
                $error_message = "Gagal memperbarui status kamar.";
            }
        } else {
            $error_message = "Gagal mengubah status transaksi.";
        }
    }

    if (isset($_POST['cancel'])) {
        // Jika tombol cancel ditekan, ubah status transaksi menjadi "Cancelled"
        $update_query = "UPDATE transaksi SET status = 'Cancelled' WHERE id_transaksi = $id_transaksi";
        if ($conn->query($update_query)) {
            $row['status'] = 'Cancelled';  // Update status di halaman
            $success_message = "Status transaksi berhasil diperbarui menjadi Cancelled.";
        } else {
            $error_message = "Gagal mengubah status transaksi.";
        }
    }

    if (isset($_POST['delete'])) {
        // Jika tombol delete ditekan, hapus transaksi dari database
        $delete_query = "DELETE FROM transaksi WHERE id_transaksi = $id_transaksi";
        if ($conn->query($delete_query)) {
            // Redirect ke halaman daftar transaksi setelah berhasil dihapus
            header("Location: list.php");  // Arahkan kembali ke halaman daftar transaksi
            exit();
        } else {
            $error_message = "Gagal menghapus transaksi.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8fafc;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #28a745;
            color: #fff;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .badge-status {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        .btn-action {
            margin: 10px;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="card">
        <div class="card-header">
            Detail Transaksi
        </div>
        <div class="card-body">
            <!-- Error or Success Message -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php elseif (isset($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <!-- Transaction Details -->
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>ID Transaksi</th>
                        <td><?php echo $row['id_transaksi'] ?? 'Data tidak tersedia'; ?></td>
                    </tr>
                    <tr>
                        <th>Nama Penyewa</th>
                        <td><?php echo $row['username'] ?? 'Data tidak tersedia'; ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <td><?php echo isset($row['tanggal_pesan']) ? date('d-m-Y', strtotime($row['tanggal_pesan'])) : 'Data tidak tersedia'; ?></td>
                    </tr>
                    <tr>
                        <th>Nomor Kamar</th>
                        <td><?php echo $row['nomor_kamar'] ?? 'Data tidak tersedia'; ?></td>
                    </tr>
                    <tr>
                        <th>Durasi</th>
                        <td><?php echo $row['durasi'] ?? 'Data tidak tersedia'; ?> bulan</td>
                    </tr>
                    <tr>
                        <th>Total Pembayaran</th>
                        <td><?php echo isset($row['total_pembayaran']) ? "Rp " . number_format($row['total_pembayaran'], 0, ',', '.') : 'Data tidak tersedia'; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-status <?php echo $row['status'] === 'pending' ? 'bg-warning text-dark' : ($row['status'] === 'paid' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo $row['status'] ?? 'Data tidak tersedia'; ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Action Buttons -->
            <?php if ($is_admin && isset($row['status']) && $row['status'] === 'pending'): ?>
                <form action="" method="POST" class="d-flex justify-content-start">
                    <button type="submit" name="approve" class="btn btn-success btn-action">Terima Transaksi</button>
                    <button type="submit" name="cancel" class="btn btn-danger btn-action">Batalkan Transaksi</button>
                </form>
            <?php endif; ?>

            <?php if ($is_admin && isset($row['status']) && $row['status'] !== 'pending'): ?>
                <form action="" method="POST" class="d-flex justify-content-end">
                    <button type="submit" name="delete" class="btn btn-danger btn-action">Hapus Transaksi</button>
                </form>
            <?php endif; ?>

            <!-- Back Button -->
            <div class="mt-3">
                <a href="list.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
