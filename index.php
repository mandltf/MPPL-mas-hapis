<?php 
session_start();  // Memulai session untuk memastikan pengguna sudah login

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] != "Admin") {
    header("Location: user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            background-color: #f0f8f1;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            
        }

        /* Dashboard Layout */
        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: #2E8B57;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 1rem;
            box-shadow: 4px 0 8px rgba(0, 0, 0, 0.1);
            border-bottom-left-radius: 15px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-radius: 10px;
            margin: 0.5rem 0;
            transition: background 0.3s, transform 0.3s;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #1e6c46;
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: linear-gradient(to bottom, #f0f8f1, #e8f5e9);
            border-bottom-right-radius: 15px;
            overflow-y: auto;
            box-shadow: -4px 0 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        /* Header Integrated */
        .header {
            background-color: #2E8B57;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .header .profile {
            display: flex;
            align-items: center;
        }
        .header .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid #fff;
        }
        .header .profile span {
            font-size: 1rem;
            font-weight: 600;
        }

        /* Card Styles */
        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card .icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background-color: #d1e7dd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2E8B57;
            font-size: 1.5rem;
            margin-right: 1.5rem;
        }
        .card h3 {
            margin: 0;
            color: #2E8B57;
        }
        .card p {
            margin: 0.5rem 0 0;
            color: #666;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #2E8B57;
            color: #fff;
        }
        
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="kamar/list.php"><i class="fas fa-bed"></i> Manajemen Kamar</a>
            <a href="transaksi/list.php"><i class="fas fa-file-invoice"></i> Manajemen Transaksi</a>
            <a href="penyewa/list.php"><i class="fas fa-users"></i> Manajemen Penyewa</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
                <div class="profile">
                    <img src="assets/avatar.webp" alt="User Profile">
                    <span>Admin</span>
                </div>
            </div>
            <br>
            <div class="card"  >
                <div class="icon"><i class="fas fa-laptop-code"  ></i></div>
                <a href="kamar/list.php" style="text-decoration : none;"><div>
                    <h3>Manajemen Kamar</h3>
                    <p>Kelola kamar kamar anda</p>
                </div></a>
            </div>
            <div class="card" >
                <div class="icon"><i class="fas fa-brain"></i></div>
                <a href="transaksi/list.php" style="text-decoration : none;"><div>
                    <h3>Manajemen Transaksi</h3>
                    <p>Kelola Transaksi Transaksi anda</p>
                </div>
            </div>
            <div class="card">
                <div class="icon"><i class="fas fa-code"></i></div>
                <a href="penyewa/list.php" style="text-decoration : none;"><div>
                    <h3>Manajemen Penyewa</h3>
                    <p>Kelola Penyewa Penyewa anda</p>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Aplikasi Manajemen Penyewaan Kamar</p>
    </footer>
</body>
</html>
