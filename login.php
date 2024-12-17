<?php
session_start();  // Memulai session untuk menyimpan status login pengguna

include('config/database.php');  // Menyertakan koneksi database

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengambil data pengguna berdasarkan username
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Jika pengguna ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($_SESSION['role'] == 'Admin') {
                header("Location: index.php");
            } else {
                header("Location: user.php");
            }
            exit();
        } else {
            $error_message = "Username atau password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-avatar">
                <img src="assets/avatar.webp" alt="User Avatar">
            </div>
            <h1 class="login-title">Login</h1>
            
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-options">
                    <label><input type="checkbox" name="remember"> Ingat Saya</label>
                    <a href="forgot.php">Lupa Password?</a>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <p class="signup-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>
