<?php
session_start();  // Mulai session
session_unset();  // Hapus semua session variables
session_destroy();  // Hancurkan session

// Redirect ke halaman login setelah logout
header("Location: login.php");
exit();
?>
