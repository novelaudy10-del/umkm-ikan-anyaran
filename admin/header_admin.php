<?php
include '../includes/config.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Ikan Anyaran</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --biru: #4B9DA9;
            --kuning: #F6F3C2;
            --orange: #E37434;
        }

        body {
            font-family: 'Inter', sans-serif;
        }
        .navbar {
            background-color: var(--biru);
            border-bottom: 4px solid var(--orange);
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.4rem;
            color: var(--kuning) !important;
            letter-spacing: 0.5px;
        }
        .nav-link {
            color: #ffffff !important;
            font-weight: 500;
            margin-left: 8px;
            transition: 0.25s ease;
            opacity: 0.85;
        }

        .nav-link:hover {
            opacity: 1;
        }
        .nav-link.active {
            color: var(--kuning) !important;
            opacity: 1;
        }
        .btn-logout {
            background-color: #91C6BC;
            color: #222;
            font-weight: 500;
            border-radius: 8px;
            padding: 6px 14px;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #7fbab0;
            color: #222;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm mb-5">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            <i class="bi bi-fish-fill me-2"></i>ANYARAN ADMIN
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>"
                       href="dashboard.php">Dashboard</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= in_array($current_page, ['produk.php','tambah_produk.php','edit_produk.php']) ? 'active' : ''; ?>"
                       href="produk.php">Data Produk</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= in_array($current_page, ['pesanan.php','detail_pesanan.php']) ? 'active' : ''; ?>"
                       href="pesanan.php">Pesanan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($current_page == 'laporan.php') ? 'active' : ''; ?>"
                       href="laporan.php">Laporan</a>
                </li>

                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a href="logout.php" class="btn-logout">Keluar</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<div class="container">
