<?php
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Darna Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            background-color: maroon;
            color: white;
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            transition: left 0.3s;
            z-index: 1000;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        .sidebar a:hover {
            background-color: #800000;
        }

        .header {
            background-color: maroon;
            color: gold;
            font-weight: bold;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            z-index: 999;
        }

        .main-content {
            margin-top: 70px;
            /* space for header */
            margin-left: 240px;
            /* space for sidebar */
            padding: 20px;
        }

        .toggle-btn {
            font-size: 24px;
            color: gold;
            background: none;
            border: none;
            outline: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -240px;
            }

            .sidebar.active {
                left: 0;
            }

            .header {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center py-4">
            <img src="../pic/logo.png" alt="Darna" width="80" height="60" class="mb-2">
            <h5 class="mb-0">Darna Admin</h5>
            <hr class="bg-light">
        </div>

        <a href="dashboard.php"><i class="bi bi-speedometer2 m-2"></i> Dashboard</a>
        <a href="home.php"><i class="bi bi-house-door m-2"></i> Home</a>
        <a href="origin.php"><i class="bi bi-book m-2"></i> Origin</a>
        <a href="powers.php"><i class="bi bi-lightning-charge m-2"></i> Powers</a>
        <a href="media.php"><i class="bi bi-film m-2"></i> Media</a>
        <a href="gallery.php"><i class="bi bi-images m-2"></i> Gallery</a>
        <a href="admin.php"><i class="bi bi-person-gear m-2"></i> Admin</a>
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="bi bi-box-arrow-left m-2"></i> Logout</a>
    </div>

    <!-- Header -->
    <div class="header">
        <button class="toggle-btn d-md-none" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <span>Welcome, <?= $_SESSION['admin_username'] ?>!</span>
        <i class="bi bi-person-circle fs-4"></i>
    </div>