<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['pelanggan'])) {
    echo "<script>location='login.php?auth=required';</script>";
    exit;
}

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang Anda kosong'); location='katalog.php';</script>";
    exit;
}

$id_pelanggan = $_SESSION['pelanggan']['id']; 
$stmt = $conn->prepare("SELECT * FROM pelanggan WHERE id = ?");
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$ambil = $stmt->get_result();
$user = $ambil->fetch_assoc();
$stmt->close();

$total_belanja = 0;
foreach ($_SESSION['keranjang'] as $key => $qty) {
    $pecah = explode('_', $key);
    $id_p = $pecah[0]; $stn = $pecah[1];
    $stmt_p = $conn->prepare("SELECT * FROM produk WHERE id_produk=?");
    $stmt_p->bind_param("s", $id_p);
    $stmt_p->execute();
    $res_p = $stmt_p->get_result();
    $data_p = $res_p->fetch_assoc();
    $stmt_p->close();
    
    $harga = ($stn == 'eceran') ? $data_p['harga_eceran'] : $data_p['harga_ikat'];
    $total_belanja += ($harga * $qty);
}

if (isset($_POST['proses_checkout'])) {
    $total_final = $_POST['total_final'];
    $tgl_pesan = date("Y-m-d H:i:s");

    $stmt_pesanan = $conn->prepare("INSERT INTO pesanan (id_pelanggan, total_bayar, tanggal_pesan, status_pesanan, jumlah_beli) 
                    VALUES (?, ?, ?, 'Baru', 0)");
    $stmt_pesanan->bind_param("ids", $id_pelanggan, $total_final, $tgl_pesan);
    
    if ($stmt_pesanan->execute()) {
        $id_pesanan_baru = $conn->insert_id;
        $stmt_pesanan->close();

        foreach ($_SESSION['keranjang'] as $key => $qty) {
            $pecah = explode('_', $key);
            $id_p = $pecah[0]; 
            $stn = $pecah[1]; 
            
            $stmt_produk = $conn->prepare("SELECT * FROM produk WHERE id_produk=?");
            $stmt_produk->bind_param("s", $id_p);
            $stmt_produk->execute();
            $res_p = $stmt_produk->get_result();
            $data_p = $res_p->fetch_assoc();
            $stmt_produk->close();
            $harga_beli = ($stn == 'eceran') ? $data_p['harga_eceran'] : $data_p['harga_ikat'];

            $kolom_stok = ($stn == 'eceran') ? 'stok_eceran' : 'stok_ikat';
            $stmt_update = $conn->prepare("UPDATE produk SET $kolom_stok = $kolom_stok - ? WHERE id_produk = ?");
            $stmt_update->bind_param("is", $qty, $id_p);
            $stmt_update->execute();
            $stmt_update->close();

            $stmt_detail = $conn->prepare("INSERT INTO pesanan_produk (id_pesanan, id_produk, satuan, jumlah, harga_satuan, harga) 
                          VALUES (?, ?, ?, ?, ?, 0)");
            $stmt_detail->bind_param("issid", $id_pesanan_baru, $id_p, $stn, $qty, $harga_beli);
            $stmt_detail->execute();
            $stmt_detail->close();
        }

        unset($_SESSION['keranjang']);
        echo "<script>location='riwayat.php';</script>";
        exit;
    } else {
        die("Gagal simpan ke database: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Ikan Anyaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        h4, h5, h6, .fw-bold { color: var(--text-dark) !important; }
        .text-muted { color: var(--text-muted) !important; }

        .checkout-card { 
            border-radius: 20px; 
            border: 2px solid var(--color-3); 
            box-shadow: 0 10px 30px rgba(75, 157, 169, 0.1); 
            background: white; 
        }

        .address-box { 
            border: 2px solid var(--color-1); 
            background-color: #fff; 
            border-radius: 15px; 
            padding: 25px; 
            position: relative; 
            transition: 0.3s;
        }
        .address-label { 
            position: absolute; 
            top: -12px; 
            left: 20px; 
            background: var(--color-2); 
            color: white; 
            padding: 2px 15px; 
            border-radius: 10px; 
            font-size: 0.7rem; 
            font-weight: bold; 
            letter-spacing: 1px;
        }

        .method-box {
            border: 1px solid var(--color-1);
            background-color: #f9fdfd;
            border-radius: 12px;
        }

        .btn-order { 
            background: var(--color-4); 
            color: white; 
            border-radius: 15px; 
            padding: 16px; 
            width: 100%; 
            font-weight: 800; 
            border: none; 
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-order:hover {
            background: #c85f24;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 116, 52, 0.3);
            color: white;
        }

        .text-teal { color: var(--color-2) !important; }
        hr { border-top: 2px dashed var(--color-1); opacity: 0.5; }
        
        .sticky-summary {
            top: 100px;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <form action="" method="post">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card checkout-card p-4 mb-4">
                    <h4 class="fw-bold mb-4"><i class="bi bi-geo-alt me-2 text-teal"></i>Konfirmasi Alamat</h4>
                    <div class="address-box mb-3">
                        <span class="address-label text-uppercase">Tujuan Pengiriman</span>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-2" style="font-size: 1.1rem;"><?= htmlspecialchars($user['nama_pelanggan'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                <p class="mb-2 fw-bold" style="color: var(--color-2);"><i class="bi bi-whatsapp"></i> <?= htmlspecialchars($user['no_whatsapp'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="mb-0 lh-base"><?= htmlspecialchars($user['alamat_lengkap'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <a href="profil.php" class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" style="border-color: var(--color-1); color: var(--color-2);">Ubah</a>
                        </div>
                    </div>
                </div>

                <div class="card checkout-card p-4">
                    <h6 class="fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;">Metode Pengiriman & Pembayaran</h6>
                    <div class="p-3 method-box d-flex align-items-center shadow-sm">
                        <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                            <i class="bi bi-truck text-teal fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold">Kurir Ikan Anyaran (COD)</div>
                            <small class="text-muted">Bayar tunai di tempat saat ikan sampai.</small>
                        </div>
                        <i class="bi bi-check-circle-fill ms-auto text-teal fs-4"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card checkout-card p-4 sticky-top sticky-summary">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Ringkasan Pesanan</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Belanja</span>
                        <span class="fw-bold">Rp <?= number_format($total_belanja); ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="text-success fw-bold">GRATIS</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Total Tagihan</span>
                        <span class="fw-bold fs-4" style="color: var(--color-4);">Rp <?= number_format($total_belanja); ?></span>
                    </div>

                    <input type="hidden" name="total_final" value="<?= $total_belanja; ?>">
                    <button type="submit" name="proses_checkout" class="btn btn-order shadow">
                        KONFIRMASI & BUAT PESANAN
                    </button>
                    
                    <p class="text-center mt-3 small text-muted">
                        <i class="bi bi-shield-check me-1"></i> Transaksi Aman & Terpercaya
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>