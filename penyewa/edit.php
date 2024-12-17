<?php
include('../config/database.php');  // Menghubungkan ke database

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];

    // Update query
    $query = "UPDATE users SET username='$username', email='$email', telepon='$telepon' WHERE id_user=$id_user";
    if ($conn->query($query) === TRUE) {
        header('Location: list.php');  // Redirect ke halaman list setelah berhasil
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    $id_user = $_GET['id'];
    $query = "SELECT * FROM users WHERE id_user = $id_user";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penyewa</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container my-5">
    <h1 class="mb-4 text-center">Edit Penyewa</h1>

    <form action="edit.php" method="POST">
        <input type="hidden" name="id_user" value="<?php echo $row['id_user']; ?>">

        <div class="mb-3">
            <label for="username" class="form-label">Nama</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <textarea name="email" id="email" class="form-control" rows="4" required><?php echo htmlspecialchars($row['email']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" name="telepon" id="telepon" class="form-control" value="<?php echo htmlspecialchars($row['telepon']); ?>" required>
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
