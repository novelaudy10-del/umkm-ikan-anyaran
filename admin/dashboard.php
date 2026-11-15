<?php
include 'header_admin.php';
?>

<style>
    :root {
        --color-1: #91C6BC;
        --color-2: #4B9DA9;
        --color-3: #F6F3C2;
        --color-4: #E37434;
        --text-dark: #1a4a52;
        --text-muted: #5a8289;
    }

    body {
        background-color: #f4f7f6;
        background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
        color: var(--text-dark);
    }

    h2, h3, h5, p, span, i, th, td {
        color: var(--text-dark) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .card-title-custom {
        font-size: .8rem;
        letter-spacing: 1px;
    }

    .welcome-section {
        background-color: #ffffff;
        border-radius: 20px;
        border: 2px solid var(--color-3);
        padding: 40px;
        margin-top: 20px;
        box-shadow: 0 10px 30px rgba(75, 157, 169, 0.05);
    }

    .welcome-text h1 {
        color: var(--color-2) !important;
        font-weight: 800;
    }

    .btn-action {
        background-color: var(--color-2);
        color: white !important;
        border: none;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-action:hover {
        background-color: var(--color-4);
        transform: translateY(-2px);
    }
</style>

<div class="container py-4">

    <div class="mb-5">
        <h2 class="fw-bold mb-1" style="color: var(--color-2) !important;">Dashboard Overview</h2>
        <p class="text-muted">Pantau kondisi stok dan pesanan Ikan Anyaran hari ini.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0" style="border-left:6px solid var(--color-2); border-radius:15px; background-color: #ffffff;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase fw-bold mb-1 card-title-custom" style="color:var(--color-2) !important;">Jenis Ikan</p>
                        <h3 class="fw-bold mb-0">
                            <?php
                            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk");
                            echo number_format(mysqli_fetch_assoc($q)['total']);
                            ?>
                        </h3>
                    </div>
                    <i class="bi bi-box-seam fs-1" style="color:var(--color-2) !important;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0" style="border-left:6px solid var(--color-1); border-radius:15px; background-color: #ffffff;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase fw-bold mb-1 card-title-custom" style="color:var(--color-1) !important;">Stok Tersedia</p>
                        <h3 class="fw-bold mb-0">
                            <?php
                            $q = mysqli_query($conn, "SELECT SUM(stok_ikat + stok_eceran) AS total FROM produk");
                            echo number_format(mysqli_fetch_assoc($q)['total'] ?? 0);
                            ?>
                        </h3>
                    </div>
                    <i class="bi bi-stack fs-1" style="color:var(--color-1) !important;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0" style="border-left:6px solid var(--color-4); border-radius:15px; background-color: #ffffff;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-uppercase fw-bold mb-1 card-title-custom" style="color:var(--color-4) !important;">Pesanan Baru</p>
                        <h3 class="fw-bold mb-0">
                            <?php
                            $q = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan WHERE status_pesanan = 'Baru'");
                            echo number_format(mysqli_fetch_assoc($q)['total']);
                            ?>
                        </h3>
                    </div>
                    <i class="bi bi-bell fs-1" style="color:var(--color-4) !important;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="welcome-section shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8 welcome-text">
                <h1 class="mb-3">Selamat Datang, Admin!</h1>
                <p class="fs-5 mb-4 text-muted">Hari ini adalah hari yang baik untuk berbisnis. Jangan lupa untuk memeriksa kualitas ikan dan memperbarui stok agar pelanggan selalu puas.</p>
                <div class="d-flex gap-3">
                    <a href="pesanan.php" class="btn btn-action shadow-sm">Cek Pesanan</a>
                    <div class="p-2 px-3 rounded-3" style="background-color: var(--color-3);">
                        <span class="small fw-bold"><i class="bi bi-clock me-2"></i><?= date('H:i'); ?> WIB</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <i class="bi bi-water" style="font-size: 120px; color: var(--color-1) !important; opacity: 0.5;"></i>
            </div>
        </div>
    </div>

</div>

<?php include 'footer_admin.php'; ?>


