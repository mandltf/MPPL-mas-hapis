<?php
include('config/database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $role = 'Penyewa';

    if ($password !== $confirm_password) {
        $error_message = "Password dan konfirmasi password tidak cocok.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, email, telepon, role) 
                VALUES ('$username', '$hashed_password', '$email', '$telepon', '$role')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            $error_message = "Terjadi kesalahan: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link rel="stylesheet" href="assets/register.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="signup-container">
        <div class="signup-card">
            <div class="signup-visual">
                <h2>Selamat Datang!</h2>
                <p>Buat akun untuk mulai petualangan Anda.</p>
                <img src="assets/rocket.webp" alt="Welcome Image">
            </div>
            <div class="signup-form">
                <h1>Daftar Akun</h1>
                <?php if (isset($error_message)): ?>
                    <p class="error-message"><?= $error_message ?></p>
                <?php endif; ?>
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="telepon" placeholder="Nomor Telepon" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    </div>
                    <button type="submit" class="btn-submit">Daftar</button>
                </form>
                <p class="login-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
