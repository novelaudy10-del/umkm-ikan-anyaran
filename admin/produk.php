<?php
include '../includes/config.php';
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

    .main-content { min-height: 100vh; padding: 40px 0; }

    .table-container { 
        background: white; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(75, 157, 169, 0.1); 
        overflow: hidden; 
        border: 2px solid var(--color-3);
    }

    .table thead { background-color: var(--color-2); }
    .table th { 
        border: none; 
        padding: 18px !important; 
        font-weight: 600; 
        text-transform: uppercase; 
        font-size: 0.85rem;
        color: white !important;
    }

    .table td { 
        padding: 15px !important; 
        border-bottom: 1px solid #f0f4f4; 
        vertical-align: middle; 
        color: var(--text-dark) !important;
    }

    h2, p, .fw-bold, i { color: var(--text-dark) !important; }
    .text-muted { color: var(--text-muted) !important; }
    
    .img-wrapper { display: flex; gap: 5px; }
    .img-product { 
        width: 50px; height: 50px; 
        object-fit: cover; 
        border-radius: 10px; 
        border: 2px solid var(--color-1);
    }

    .badge-stock-ikat { 
        background-color: var(--color-1); 
        color: var(--text-dark); padding: 6px; border-radius: 6px; 
        display: block; margin-bottom: 4px; text-align: center; font-size: 0.75rem; font-weight: 700;
    }
    .badge-stock-eceran { 
        background-color: var(--color-4); 
        color: white; padding: 6px; border-radius: 6px; 
        display: block; text-align: center; font-size: 0.75rem; font-weight: 600;
    }
    
    .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: 0.3s; text-decoration: none; }
    .btn-action.text-primary { color: var(--color-2) !important; border-color: var(--color-2) !important; }
    .btn-action.text-danger { color: var(--color-4) !important; border-color: var(--color-4) !important; }

    .price-box { line-height: 1.4; }
    .price-label { font-size: 0.7rem; color: var(--text-muted); display: block; font-weight: 600; }
</style>

<div class="main-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--color-2) !important;">Manajemen Stok Ikan</h2>
                <p class="text-muted small">Kelola data produk, harga, dan ketersediaan stok Anda secara akurat.</p>
            </div>
            <a href="tambah_produk.php" class="btn shadow-sm px-4 py-2 text-white border-0" style="background-color: var(--color-4); border-radius: 12px; font-weight: 600; text-decoration: none;">
                <i class="bi bi-plus-lg me-2 text-white"></i>Tambah Produk
            </a>
        </div>

        <div class="table-container shadow-sm">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Produk (Utama & Detail)</th>
                        <th>Kategori</th>
                        <th>Informasi Harga</th>
                        <th>Status Stok</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $ambil = mysqli_query($conn, "SELECT * FROM produk ORDER BY id_produk DESC");
                    while($row = mysqli_fetch_assoc($ambil)): 
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="img-wrapper me-3">
                                    <img src="../uploads/<?= $row['foto_produk']; ?>" class="img-product" onerror="this.src='https://placehold.co/100x100?text=No+Img'">
                                    <?php if(!empty($row['foto_produk2'])): ?>
                                        <img src="../uploads/<?= $row['foto_produk2']; ?>" class="img-product" onerror="this.src='https://placehold.co/100x100?text=No+Img'">
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="fw-bold mb-0"><?= $row['nama_produk']; ?></div>
                                    <small class="text-muted">ID: #<?= $row['id_produk']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background-color: var(--color-3); color: var(--text-dark); border-radius: 6px; padding: 6px 10px; border: 1px solid var(--color-1);">
                                <?= $row['jenis_ikan']; ?>
                            </span>
                        </td>
                        <td>
                            <div class="price-box">
                                <span class="price-label">ECERAN</span>
                                <div class="fw-bold">Rp <?= number_format($row['harga_eceran']); ?></div>
                                <span class="price-label mt-1">PER IKAT</span>
                                <div class="fw-bold" style="color: var(--color-4) !important;">Rp <?= number_format($row['harga_ikat']); ?></div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-stock-ikat shadow-sm">
                                <i class="bi bi-box-seam me-1"></i> <?= $row['stok_ikat']; ?> Ikat
                            </span>
                            <span class="badge-stock-eceran shadow-sm">
                                <i class="bi bi-shop me-1"></i> <?= $row['stok_eceran']; ?> Eceran
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="edit_produk.php?id=<?= $row['id_produk']; ?>" class="btn-action bg-white text-primary border shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="hapus_produk.php?id=<?= $row['id_produk']; ?>" class="btn-action bg-white text-danger border shadow-sm" 
                                   onclick="return confirm('Hapus produk <?= $row['nama_produk']; ?>?')" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>
