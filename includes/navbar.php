<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$total_item = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $qty) {
        $total_item += $qty;
    }
}
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: #4B9DA9;">
            IKAN <span style="color: #E37434;">ANYARAN</span>
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <div class="ms-auto d-flex align-items-center">
                <ul class="navbar-nav me-3">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link fw-semibold px-3 d-flex align-items-center">
                            <i class="bi bi-house-door me-2"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="katalog.php" class="nav-link fw-semibold px-3 d-flex align-items-center">
                            <i class="bi bi-journal-bookmark me-2"></i> Katalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link fw-semibold px-3 d-flex align-items-center">
                            <i class="bi bi-clock-history me-2"></i> Riwayat
                        </a>
                    </li>
                </ul>
                
                <a href="keranjang.php" class="position-relative text-dark d-inline-flex align-items-center" id="cart-icon" style="text-decoration: none;">
                    <i class="bi bi-cart3" style="font-size: 1.6rem;"></i>
                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                          style="display: <?= ($total_item > 0) ? 'block' : 'none'; ?>; font-size: 0.7rem; min-width: 18px;">
                        <?= $total_item; ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link { 
        transition: 0.3s; 
        display: flex;
        align-items: center;
        color: #555 !important;
    }
    .nav-link:hover { color: #E37434 !important; }
    .nav-link i { color: #4B9DA9; font-size: 1.1rem; }
    .nav-link:hover i { color: #E37434; }

    @keyframes bounce {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.5); }
    }
    .cart-bump { animation: bounce 0.4s ease-in-out; }
    
    @media (max-width: 991.98px) {
        .navbar-nav { margin-bottom: 15px; }
        .ms-auto { flex-direction: column; align-items: flex-start !important; }
        .nav-link { padding: 10px 0 !important; }
    }
</style>