<?php 
session_start();
include 'includes/config.php'; 

$ambil = $conn->query("SELECT * FROM produk ORDER BY id_produk DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk | Ikan Anyaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --teal: #4B9DA9; 
            --orange: #E37434; 
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7f6;
            background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
        }

        .product-card { 
            border: none; 
            border-radius: 20px; 
            overflow: hidden; 
            background: white; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: 0.4s;
        }

        .product-card:hover { 
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .img-wrapper {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.5s ease;
        }

        .img-secondary {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .product-card:hover .img-secondary {
            opacity: 1;
        }

        .badge-status {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 50px;
        }

        .price-tag-box {
            background: #fff9f5;
            border: 1px solid #ffefe5;
            border-radius: 12px;
            padding: 10px;
        }

        .unit-info {
            font-size: 0.75rem;
            color: #888;
            font-weight: 400;
        }

        .btn-pesan {
            background: var(--orange);
            color: white;
            border-radius: 12px;
            font-weight: 700;
            transition: 0.3s;
            border: none;
        }

        .btn-pesan:hover {
            background: #c55a1d;
            color: white;
        }
    </style>
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-800">Katalog Ikan Anyaran</h1>
        <p class="text-muted">Ikan Goreng Crispy Berbagai Jenis - Eceran & Grosir</p>
        <div style="width: 50px; height: 4px; background: var(--teal); margin: 15px auto;"></div>
    </div>

    <div class="row g-4">
        <?php while($produk = $ambil->fetch_assoc()): 
            $is_available = ($produk['stok_ikat'] > 0 || $produk['stok_eceran'] > 0);
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="product-card h-100 d-flex flex-column">
                
                <div class="img-wrapper">
                    <img src="uploads/<?= $produk['foto_produk']; ?>" class="img-primary" onerror="this.src='https://placehold.co/400x400?text=Ikan+Anyaran'">
                    
                    <?php if(!empty($produk['foto_produk2'])): ?>
                    <img src="uploads/<?= $produk['foto_produk2']; ?>" class="img-secondary" onerror="this.src='https://placehold.co/400x400?text=Ikan+Anyaran'">
                    <?php endif; ?>

                    <span class="badge-status <?= $is_available ? 'bg-success' : 'bg-danger' ?> text-white">
                        <?= $is_available ? 'TERSEDIA' : 'HABIS' ?>
                    </span>
                </div>

                <div class="card-body p-4 d-flex flex-column">
                    <small class="fw-bold" style="color: var(--teal)"><?= $produk['jenis_ikan']; ?></small>
                    <h5 class="fw-bold my-2"><?= $produk['nama_produk']; ?></h5>
                    
                    <div class="price-tag-box mb-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="d-block fw-600" style="font-size: 0.9rem;">Eceran</span>
                                <span class="unit-info">(1 pcs)</span>
                            </div>
                            <span class="fw-bold">Rp <?= number_format($produk['harga_eceran']); ?></span>
                        </div>
                        
                        <hr class="my-2" style="opacity: 0.1;">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="d-block fw-600" style="font-size: 0.9rem;">Per Ikat</span>
                                <span class="unit-info">(5 pcs)</span>
                            </div>
                            <span class="fw-bold" style="color: var(--orange)">Rp <?= number_format($produk['harga_ikat']); ?></span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="detail_produk.php?id=<?= $produk['id_produk']; ?>" class="btn btn-pesan py-2 <?= !$is_available ? 'disabled' : '' ?>">
                            <i class="bi bi-cart3 me-2"></i>Pesan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>