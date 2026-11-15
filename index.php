<?php 
session_start();
include 'includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | Ikan Anyaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #3F9AAE;
            --secondary: #79C9C5;
            --soft: #FFE2AF;
            --accent: #E37434;
        }

        body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #79C9C5;
    scroll-behavior: smooth;

    background-color: #f4f7f6;
    background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
    }

       .hero-section {
    background: url('uploads/foto ikan anyaran 1.jpeg');
    background-size: cover;
    background-position: center;
    padding: 150px 0;
    color: white;
    border-bottom-left-radius: 50px;
    border-bottom-right-radius: 50px;
    }

        }

        .btn-main {
            background: var(--primary);
            color: white;
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 25px;
            border: none;
            transition: .3s;
        }
        .btn-main:hover {
            background: #327e8f;
            transform: translateY(-2px);
        }

        .btn-outline-main {
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 25px;
            transition: .3s;
            background: transparent;
        }
        .btn-outline-main:hover {
            background: var(--primary);
            color: white;
        }

        .profil-img {
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,.1);
        }

        .feature-card {
            border-radius: 20px;
            padding: 30px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,.05);
            transition: .3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: var(--soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--accent);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
            <span style="color:#3F9AAE;">IKAN</span> <span class="ms-1" style="color: #E37434;">ANYARAN</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="ms-auto d-flex align-items-center gap-2 mt-3 mt-lg-0">

                <a href="katalog.php" class="btn btn-outline-main btn-sm">Katalog</a>

                <?php if(isset($_SESSION['pelanggan'])): ?>
                    <span class="small d-none d-md-block">
                        Halo, <b><?= $_SESSION['pelanggan']['nama_pelanggan']; ?></b>
                    </span>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-main btn-sm">Masuk</a>
                    <a href="registrasi.php" class="btn btn-main btn-sm">Daftar</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

<header class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3 text-uppercase">
            Ikan Goreng Tepung <br>
            <span style="color:var(--soft)">Kualitas Premium</span>
        </h1>
        <p class="lead mb-5">
            Olahan ikan segar hasil nelayan Gebang, Cirebon.
            Renyah, gurih, dan bergizi.
        </p>
        <a href="#profil" class="btn btn-light btn-lg fw-bold px-5" style="color:var(--primary)">
            Kenali Kami
        </a>
    </div>
</header>

<section id="profil" class="py-5 mt-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4">
                <div class="profil-img">
                    <img src="uploads/foto ikan anyaran.jpeg" class="img-fluid" alt="Ikan Anyaran">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h6 class="fw-bold text-uppercase" style="color:var(--primary)">Profil UMKM</h6>
                <h2 class="fw-bold mb-4">UMKM Ikan Anyaran</h2>
                <p class="text-muted" style="line-height:1.8">
                    Berdiri sejak 2019, UMKM Ikan Anyaran mengolah ikan laut
                    dan air tawar menjadi produk ikan goreng tepung berkualitas.
                    Produksi harian bisa mencapai 50–80 kg dan dipasarkan ke berbagai pasar.
                </p>

                <a href="katalog.php" class="btn btn-main mt-3">
                    Lihat Katalog Produk
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background:#f8fcfb">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Keunggulan Kami</h2>
            <div style="width:60px;height:4px;background:var(--primary);margin:20px auto"></div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box mx-auto"><i class="bi bi-geo-alt"></i></div>
                    <h5 class="fw-bold">Bahan Lokal</h5>
                    <p class="text-muted small">Ikan segar dari Laut Gebang & pemasok lokal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box mx-auto"><i class="bi bi-droplet"></i></div>
                    <h5 class="fw-bold">Tanpa Pengawet</h5>
                    <p class="text-muted small">Diproses alami tanpa bahan kimia.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="icon-box mx-auto"><i class="bi bi-hand-thumbs-up"></i></div>
                    <h5 class="fw-bold">Produksi Stabil</h5>
                    <p class="text-muted small">50–80 kg per hari.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 bg-white border-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start mb-3">
                <h5 class="fw-bold mb-0" style="color:var(--primary)">IKAN ANYARAN</h5>
                <small class="text-muted">Olahan Ikan Goreng Tepung</small>
            </div>

            <div class="col-md-4 text-center mb-3">
                <a href="https://wa.me/6287854878541" target="_blank"
                   class="btn btn-success rounded-pill fw-bold">
                    <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
                </a>
            </div>

            <div class="col-md-4 text-center text-md-end">
                <small class="text-muted">&copy; 2025 Ikan Anyaran</small><br>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>