<?php
$host = "localhost";
$dbname = "db_penyewaan";
$username = "root";
$password = "";

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} else {
    echo "";
}
?>
