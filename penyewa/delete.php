<?php
include('../config/database.php');

session_start();  // Memulai session untuk memastikan pengguna sudah login

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Jika belum login, arahkan ke halaman login
    exit();  // Pastikan eksekusi berhenti setelah redirect
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id_user = $id";
    
    if ($conn->query($delete_query) === TRUE) {
        header('Location: list.php'); // Setelah berhasil, redirect ke halaman list penyewa
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
