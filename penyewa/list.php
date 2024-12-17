<?php
include('../config/database.php');  // Menghubungkan ke database

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

// Mengambil daftar pengguna dengan role 'Penyewa'
$query = "SELECT * FROM users WHERE role = 'Penyewa'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penyewa</title>
    <link rel="stylesheet" href="../assets/style1.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="../index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="../index.php"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Daftar Penyewa</h1>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['telepon']; ?></td>
                                <td>
                                    <!-- Tombol Edit dan Hapus -->
                                    <a href="edit.php?id=<?php echo $row['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?php echo $row['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php $conn->close(); ?>
