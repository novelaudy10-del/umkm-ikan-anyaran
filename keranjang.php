<?php
session_start();
include 'includes/config.php'; 

// --- LOGIKA PROSES KERANJANG (BAGIAN YANG DIPERBAIKI) ---
if (isset($_GET['action']) && $_GET['action'] == "add") {
    $id_produk = $_POST['id_produk'];
    $jumlah = (int)$_POST['jumlah'];
    $satuan = $_POST['satuan']; 
    $cart_key = $id_produk . "_" . $satuan;

    // 1. Ambil stok asli dari Database sebagai validasi final
    $stmt = $conn->prepare("SELECT stok_eceran, stok_ikat FROM produk WHERE id_produk = ?");
    $stmt->bind_param("s", $id_produk);
    $stmt->execute();
    $ambil = $stmt->get_result();
    $data_stok = $ambil->fetch_assoc();
    $stmt->close();

    if (!$data_stok) {
        echo "Produk tidak ditemukan";
        exit;
    }

    // 2. Tentukan batas stok berdasarkan pilihan user (eceran/ikat)
    $stok_tersedia = ($satuan == 'eceran') ? (int)$data_stok['stok_eceran'] : (int)$data_stok['stok_ikat'];
    
    // 3. Cek jumlah yang SUDAH ADA di keranjang sebelumnya
    $jumlah_di_keranjang = isset($_SESSION['keranjang'][$cart_key]) ? $_SESSION['keranjang'][$cart_key] : 0;
    
    // 4. Hitung total: yang sudah ada + yang mau ditambah sekarang
    $total_permintaan = $jumlah_di_keranjang + $jumlah;

    // 5. VALIDASI FINAL: Jika total melebihi stok, STOP dan kirim error
    if ($total_permintaan > $stok_tersedia) {
        echo "Stok tidak cukup! Di keranjang Anda sudah ada $jumlah_di_keranjang, sisa stok tersedia hanya " . ($stok_tersedia - $jumlah_di_keranjang);
        exit;
    }

    // Jika lewat validasi, baru masukkan ke session
    if (isset($_GET['direct_checkout']) && $_GET['direct_checkout'] == "1") {
        unset($_SESSION['keranjang']); // Bersihkan keranjang jika "Beli Sekarang"
    }

    if (isset($_SESSION['keranjang'][$cart_key])) {
        $_SESSION['keranjang'][$cart_key] += $jumlah;
    } else {
        $_SESSION['keranjang'][$cart_key] = $jumlah;
    }

    echo isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; 
    exit; 
}

// Logika hapus item
if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $key_hapus = $_GET['id'];
    unset($_SESSION['keranjang'][$key_hapus]);
    echo "<script>location='keranjang.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Ikan Anyaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-teal: #4B9DA9;
            --shopee-orange: #E37434;
            --light-bg: #f8fafb;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7f6;
            background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
            color: #333;
        }
        .cart-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            background: white;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
        }
        .table thead th {
            border: none;
            background-color: #f1f5f9;
            padding: 15px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }
        .text-orange { color: var(--shopee-orange); }
        .badge-satuan {
            background-color: #e0f2f1;
            color: var(--primary-teal);
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 8px;
        }
        .summary-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            position: sticky;
            top: 20px;
        }
        .btn-checkout {
            background-color: var(--shopee-orange);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-checkout:hover {
            background-color: #c55a1d;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(227, 116, 52, 0.2);
            color: white;
        }
        .empty-state { padding: 80px 20px; }
        .btn-belanja {
            background-color: var(--primary-teal);
            color: white;
            padding: 12px 35px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
        }

        .modal-content { border-radius: 24px; border: none; }
        .btn-modal-login { background-color: var(--primary-teal); color: white; border-radius: 12px; font-weight: 600; }
        .btn-modal-login:hover { background-color: #3a7d87; color: white; }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="row g-4">
        
        <?php if (empty($_SESSION['keranjang'])): ?>
            <div class="col-12 text-center empty-state">
                <div class="mb-4">
                    <i class="bi bi-cart-x text-muted opacity-25" style="font-size: 8rem;"></i>
                </div>
                <h3 class="fw-bold mb-2">Yah, Keranjangmu Masih Kosong</h3>
                <p class="text-muted mb-4">Yuk, cari ikan segar pilihanmu sekarang juga!</p>
                <a href="katalog.php" class="btn btn-belanja">
                    Mulai Belanja Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        <?php else: ?>
            
            <div class="col-lg-8">
                <div class="card cart-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Keranjang Belanja</h4>
                        <a href="katalog.php" class="text-decoration-none small fw-bold" style="color: var(--primary-teal);">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Produk Lain
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grand_total = 0;
                                foreach ($_SESSION['keranjang'] as $key => $qty):
                                    $pecah = explode('_', $key);
                                    $id_p = $pecah[0]; 
                                    $stn = $pecah[1];
                                    
                                    $stmt_cart = $conn->prepare("SELECT * FROM produk WHERE id_produk=?");
                                    $stmt_cart->bind_param("s", $id_p);
                                    $stmt_cart->execute();
                                    $data = $stmt_cart->get_result()->fetch_assoc();
                                    $stmt_cart->close();
                                    $harga = ($stn == 'eceran') ? $data['harga_eceran'] : $data['harga_ikat'];
                                    $sub = $harga * $qty; 
                                    $grand_total += $sub;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="uploads/<?= htmlspecialchars($data['foto_produk'], ENT_QUOTES, 'UTF-8'); ?>" class="product-img me-3">
                                            <div>
                                                <div class="fw-bold"><?= htmlspecialchars($data['nama_produk'], ENT_QUOTES, 'UTF-8'); ?></div>
                                                <span class="badge badge-satuan small mt-1"><?= htmlspecialchars(ucfirst($stn), ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp <?= number_format($harga); ?></td>
                                    <td class="fw-bold"><?= $qty; ?></td>
                                    <td class="text-orange fw-bold">Rp <?= number_format($sub); ?></td>
                                    <td>
                                        <a href="keranjang.php?action=delete&id=<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" class="text-danger" title="Hapus Item">
                                            <i class="bi bi-trash3 fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-box">
                    <h5 class="fw-bold mb-4 border-bottom pb-3">Ringkasan Belanja</h5>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Total Harga (<?= count($_SESSION['keranjang']); ?> Produk)</span>
                        <span>Rp <?= number_format($grand_total); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Biaya Pengiriman</span>
                        <span class="text-success fw-bold">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 mt-4">
                        <span class="fw-bold h5">Total Tagihan</span>
                        <span class="fw-bold h5 text-orange">Rp <?= number_format($grand_total); ?></span>
                    </div>
                    
                    <a href="checkout.php" class="btn btn-checkout w-100 shadow-sm">
                        CHECKOUT SEKARANG
                    </a>
                    
                    <p class="text-center small text-muted mt-3">
                        <i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terenkripsi
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalAuth" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="bi bi-person-circle text-muted opacity-50" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Mau Checkout?</h4>
                <p class="text-muted mb-4">Silakan masuk ke akunmu atau daftar dulu ya untuk melanjutkan pesanan.</p>
                <div class="d-grid gap-2">
                    <a href="login.php" class="btn btn-modal-login py-3">Login / Masuk</a>
                    <a href="registrasi.php" class="btn btn-light py-3 fw-bold">Daftar Akun Baru</a>
                </div>
                <button type="button" class="btn btn-link text-muted mt-3 text-decoration-none" data-bs-dismiss="modal">Nanti Saja</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auth') === 'required') {
            const authModal = new bootstrap.Modal(document.getElementById('modalAuth'));
            authModal.show();
        }
    });
</script>

</body>
</html>