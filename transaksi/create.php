<?php
include('../config/database.php');  // Menghubungkan ke database

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];  // Misalnya role disimpan di session

// Jika pengguna bukan admin, otomatis menggunakan id_user dari session
if ($role !== 'admin') {
    $query_user = "SELECT id_user FROM users WHERE username = '$username'";
    $result_user = $conn->query($query_user);
    $row_user = $result_user->fetch_assoc();
    $id_user_default = $row_user['id_user'];  // Ambil id_user berdasarkan session
} else {
    // Jika admin, pengguna dapat memilih id_user lain
    $id_user_default = null; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Jika bukan admin, id_user diambil dari session, selain itu diambil dari input POST
    $id_user = $role === 'admin' ? $_POST['id_user'] : $id_user_default;
    $tanggal_pesan = $_POST['tanggal_pesan'];
    $id_kamar = $_POST['id_kamar'];
    $durasi = $_POST['durasi'];

    // Menambahkan pesanan
    $query_pesanan = "INSERT INTO memesan (id_user, tanggal_pesan) VALUES ('$id_user', '$tanggal_pesan')";
    if ($conn->query($query_pesanan) === TRUE) {
        $id_pesanan = $conn->insert_id;

        // Menambahkan transaksi
        $query_transaksi = "INSERT INTO transaksi (id_pesanan, total_pembayaran) VALUES ('$id_pesanan', 0)";
        if ($conn->query($query_transaksi) === TRUE) {
            $id_transaksi = $conn->insert_id;

            // Menambahkan penggunaan kamar
            $query_penggunaan = "INSERT INTO menggunakan (id_transaksi, id_kamar, durasi) VALUES ('$id_transaksi', '$id_kamar', '$durasi')";
            if ($conn->query($query_penggunaan) === TRUE) {
                // Menghitung total pembayaran
                $query_harga = "SELECT harga FROM kamar WHERE id_kamar = $id_kamar";
                $result_harga = $conn->query($query_harga);
                $row_harga = $result_harga->fetch_assoc();
                $total_pembayaran = $row_harga['harga'] * $durasi;

                // Memperbarui total pembayaran
                $update_transaksi = "UPDATE transaksi SET total_pembayaran = $total_pembayaran WHERE id_transaksi = $id_transaksi";
                $conn->query($update_transaksi);

                // Mengupdate status kamar menjadi 'Terisi'
                $update_kamar = "UPDATE kamar SET status = 'Terisi' WHERE id_kamar = $id_kamar";
                $conn->query($update_kamar);

                header('Location: list.php');
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4 text-center">Tambah Transaksi</h1>

    <!-- Form untuk menambahkan transaksi -->
    <form action="create.php" method="POST">
        <div class="mb-3">
            <label for="id_user" class="form-label">Nama Penyewa</label>
            <select name="id_user" class="form-select" required <?php echo ($role !== 'admin') ? 'disabled' : ''; ?>>
                <?php
                if ($role === 'admin') {
                    // Jika admin, tampilkan semua pengguna
                    $query_users = "SELECT * FROM users";
                    $result_users = $conn->query($query_users);
                    while ($row = $result_users->fetch_assoc()) {
                        echo "<option value='".$row['id_user']."'>".$row['username']."</option>";
                    }
                } else {
                    // Jika bukan admin, otomatis pilih user yang login dan non-aktifkan dropdown
                    echo "<option value='".$id_user_default."' selected>".$username."</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal_pesan" class="form-label">Tanggal Pemesanan</label>
            <input type="date" name="tanggal_pesan" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_kamar" class="form-label">Kamar</label>
            <select name="id_kamar" class="form-select" required>
                <?php
                // Query untuk mengambil kamar yang statusnya bukan 'Terisi'
                $query_kamar = "SELECT * FROM kamar WHERE status != 'Terisi'";
                $result_kamar = $conn->query($query_kamar);
                
                // Periksa apakah ada kamar yang ditemukan
                if ($result_kamar->num_rows > 0) {
                    while ($row = $result_kamar->fetch_assoc()) {
                        echo "<option value='".$row['id_kamar']."'>".$row['nomor_kamar']." - ".$row['tipe_kamar']."</option>";
                    }
                } else {
                    echo "<option value='' disabled>No kamar tersedia</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="durasi" class="form-label">Durasi (bulan)</label>
            <input type="number" name="durasi" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="list.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- JavaScript Bootstrap CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php $conn->close(); ?>
