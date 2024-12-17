<?php
include '../config/database.php';
session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}
$id = $_GET['id'];

// Menggunakan prepared statement dengan MySQLi untuk menghapus data
$stmt = $conn->prepare("DELETE FROM kamar WHERE id_kamar = ?");
$stmt->bind_param("i", $id); // 'i' untuk integer
$stmt->execute();

header("Location: list.php");
exit();
?>
