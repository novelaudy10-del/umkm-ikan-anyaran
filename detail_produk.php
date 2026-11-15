<?php 
session_start();
include 'includes/config.php'; 

$id_produk = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$ambil = $conn->query("SELECT * FROM produk WHERE id_produk = '$id_produk'");
$detail = $ambil->fetch_assoc();

if (!$detail) {
    echo "<script>alert('Produk tidak ditemukan'); location='katalog.php';</script>";
    exit;
}

$stok_eceran = (int)$detail['stok_eceran'];
$stok_ikat = (int)$detail['stok_ikat'];

$is_eceran_empty = ($stok_eceran <= 0);
$is_ikat_empty = ($stok_ikat <= 0);
$all_empty = ($is_eceran_empty && $is_ikat_empty);

$is_logged_in = isset($_SESSION['pelanggan']) ? 'true' : 'false';

// Menentukan stok awal untuk atribut 'max' pada input
$stok_awal = !$is_eceran_empty ? $stok_eceran : ($is_ikat_empty ? 0 : $stok_ikat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $detail['nama_produk']; ?> | Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --teal: #4B9DA9; 
            --orange: #E37434; 
            --light-teal: #f0f9fa;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7f6;
            background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
        }

        .product-img { 
            border-radius: 24px; 
            width: 100%; 
            height: 500px; 
            object-fit: cover; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .detail-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-cart { 
            background: var(--teal); 
            color: white; 
            border: none; 
            border-radius: 16px; 
            font-weight: 700; 
            transition: 0.3s; 
        }

        .btn-cart:hover:not(:disabled) {
            background: #3a7d87;
            transform: translateY(-2px);
            color: white;
        }

        .btn-buy {
            background-color: var(--orange);
            color: white;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            transition: 0.3s;
        }

        .btn-buy:hover:not(:disabled) {
            background-color: #c55a1d;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 10px 20px rgba(227, 116, 52, 0.2);
        }

        .selection-card { 
            border: 2px solid #eee; 
            border-radius: 18px; 
            cursor: pointer; 
            transition: 0.3s; 
            background: white; 
        }

        .selection-card.disabled {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
            pointer-events: none; 
            filter: grayscale(1);
            opacity: 0.7;
        }

        .btn-check:checked + .selection-card { 
            border-color: var(--teal); 
            background-color: var(--light-teal); 
            box-shadow: 0 5px 15px rgba(75, 157, 169, 0.1);
        }

        .btn-back {
            color: var(--teal);
            transition: 0.3s;
        }
        .btn-back:hover {
            color: var(--orange);
            transform: translateX(-5px);
        }

        .cart-bump {
            animation: bump 0.3s ease-out;
        }
        @keyframes bump {
            0% { transform: scale(1); }
            50% { transform: scale(1.4); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="mb-4">
        <a href="katalog.php" class="text-decoration-none fw-bold small btn-back d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> KEMBALI KE KATALOG
        </a>
    </div>

    <div class="detail-container shadow-sm">
        <div class="row g-5">
            <div class="col-md-6">
                <img src="uploads/<?= $detail['foto_produk']; ?>" class="product-img" alt="Produk">
            </div>

            <div class="col-md-6">
                <small class="fw-bold text-uppercase" style="color: var(--teal); letter-spacing: 1px;">
                    Ikan Anyaran Crispy
                </small>
                <h1 class="fw-800 mb-4 mt-2" style="font-size: 2.5rem; color: #2d3436;"><?= $detail['nama_produk']; ?></h1>
                
                <form id="formTambahKeranjang">
                    <input type="hidden" name="id_produk" value="<?= $detail['id_produk']; ?>">
                    
                    <p class="fw-bold mb-3 text-muted small text-uppercase">Pilih Varian Satuan:</p>
                    
                    <div class="mb-3">
                        <input type="radio" name="satuan" value="eceran" id="satuanEceran" class="btn-check" 
                               data-stok="<?= $stok_eceran ?>"
                               <?= (!$is_eceran_empty) ? 'checked' : ''; ?> 
                               <?= ($is_eceran_empty) ? 'disabled' : ''; ?>>
                        <label class="selection-card p-3 d-block <?= ($is_eceran_empty) ? 'disabled' : ''; ?>" for="satuanEceran">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="d-block fw-bold" style="font-size: 1.1rem;">Eceran (1 pcs)</span>
                                    <small class="<?= ($is_eceran_empty) ? 'text-danger' : 'text-muted'; ?>">
                                        <i class="bi <?= ($is_eceran_empty) ? 'bi-x-circle' : 'bi-box'; ?> me-1"></i>
                                        Stok: <?= ($is_eceran_empty) ? 'Habis' : $stok_eceran . ' tersedia'; ?>
                                    </small>
                                </div>
                                <span class="fw-bold h5 mb-0" style="color: #2d3436;">Rp <?= number_format($detail['harga_eceran']); ?></span>
                            </div>
                        </label>
                    </div>

                    <div class="mb-4">
                        <input type="radio" name="satuan" value="ikat" id="satuanIkat" class="btn-check" 
                               data-stok="<?= $stok_ikat ?>"
                               <?= ($is_eceran_empty && !$is_ikat_empty) ? 'checked' : ''; ?> 
                               <?= ($is_ikat_empty) ? 'disabled' : ''; ?>>
                        <label class="selection-card p-3 d-block <?= ($is_ikat_empty) ? 'disabled' : ''; ?>" for="satuanIkat">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="d-block fw-bold" style="font-size: 1.1rem;">Per Ikat (5 pcs)</span>
                                    <small class="<?= ($is_ikat_empty) ? 'text-danger' : 'text-muted'; ?>">
                                        <i class="bi <?= ($is_ikat_empty) ? 'bi-x-circle' : 'bi-boxes'; ?> me-1"></i>
                                        Stok: <?= ($is_ikat_empty) ? 'Habis' : $stok_ikat . ' tersedia'; ?>
                                    </small>
                                </div>
                                <span class="fw-bold h5 mb-0" style="color: var(--orange);">Rp <?= number_format($detail['harga_ikat']); ?></span>
                            </div>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold mb-2 small text-muted text-uppercase">Jumlah Pembelian:</label>
                        <div class="d-flex align-items-center">
                            <input type="number" id="inputJumlah" name="jumlah" class="form-control form-control-lg shadow-sm border-0 text-center" 
                                   value="1" min="1" max="<?= $stok_awal ?>" style="width: 100px; border-radius: 12px; background: #f1f5f9;"
                                   <?= ($all_empty) ? 'disabled' : ''; ?>>
                        </div>
                        <small id="pesanStok" class="text-danger mt-1 d-none">Jumlah melebihi stok!</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <button type="button" id="btnTambah" class="btn btn-cart w-100 py-3" <?= ($all_empty) ? 'disabled' : ''; ?>>
                                <i class="bi bi-cart-plus me-2"></i><?= ($all_empty) ? 'Stok Habis' : '+ Keranjang'; ?>
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="btnBeliSekarang" class="btn btn-buy w-100 py-3" <?= ($all_empty) ? 'disabled' : ''; ?>>
                                <i class="bi bi-lightning-fill me-2"></i><?= ($all_empty) ? 'Habis' : 'Beli Sekarang'; ?>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-5 p-3 border-top d-flex align-items-center">
                   <div class="me-3 fs-3" style="color: var(--teal);"><i class="bi bi-shield-check"></i></div>
                   <div>
                       <small class="d-block fw-bold">Jaminan Mutu Anyaran</small>
                       <small class="text-muted small">Menggunakan ikan segar dan pilihan.</small>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const isUserLoggedIn = <?= $is_logged_in ?>;
const inputJumlah = document.getElementById('inputJumlah');
const radioSatuan = document.querySelectorAll('input[name="satuan"]');
const pesanStok = document.getElementById('pesanStok');

radioSatuan.forEach(radio => {
    radio.addEventListener('change', function() {
        const stokTersedia = parseInt(this.getAttribute('data-stok'));
        inputJumlah.max = stokTersedia;
        if (parseInt(inputJumlah.value) > stokTersedia) {
            inputJumlah.value = stokTersedia;
        }
    });
});

inputJumlah.addEventListener('input', function() {
    const max = parseInt(this.max);
    const val = parseInt(this.value);
    if (val > max) {
        this.value = max;
        pesanStok.classList.remove('d-none');
        setTimeout(() => pesanStok.classList.add('d-none'), 2000);
    }
});

function prosesKeranjang(isBeliSekarang) {
    const btnTambah = document.getElementById('btnTambah');
    const btnBeli = document.getElementById('btnBeliSekarang');
    const form = document.getElementById('formTambahKeranjang');
    const formData = new FormData(form);

    // --- PERBAIKAN UTAMA: CEK STOK DULU SEBELUM APA PUN ---
    const qty = parseInt(inputJumlah.value);
    const maxQty = parseInt(inputJumlah.max);

    if (qty > maxQty) {
        alert('Maaf, stok hanya tersedia ' + maxQty + ' unit. Silakan ubah jumlah pesanan Anda.');
        inputJumlah.value = maxQty;
        return; // Fungsi berhenti di sini, tidak akan mengirim data ke PHP
    }
    // ----------------------------------------------------

    if (isBeliSekarang && !isUserLoggedIn) {
        let urlAdd = 'keranjang.php?action=add&direct_checkout=1';
        fetch(urlAdd, { method: 'POST', body: formData })
        .then(() => {
            window.location.href = 'keranjang.php?auth=required';
        });
        return;
    }

    const activeBtn = isBeliSekarang ? btnBeli : btnTambah;
    const originalHTML = activeBtn.innerHTML;
    
    activeBtn.disabled = true;
    activeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>...';

    let url = 'keranjang.php?action=add';
    if(isBeliSekarang) {
        url += '&direct_checkout=1';
    }

    fetch(url, { 
        method: 'POST', 
        body: formData 
    })
    .then(response => response.text())
    .then(data => {
        if (data.toLowerCase().includes("stok tidak cukup")) {
            alert(data);
            activeBtn.disabled = false;
            activeBtn.innerHTML = originalHTML;
            return;
        }

        if(isBeliSekarang) {
            window.location.href = 'checkout.php';
        } else {
            const badge = document.getElementById('cart-badge');
            if(badge) {
                badge.innerText = data; 
                badge.style.display = 'block';
                badge.classList.remove('cart-bump');
                void badge.offsetWidth; 
                badge.classList.add('cart-bump');
            }

            activeBtn.disabled = false;
            activeBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Sukses!';
            activeBtn.style.backgroundColor = '#27ae60';

            setTimeout(() => { 
                activeBtn.innerHTML = originalHTML; 
                activeBtn.style.backgroundColor = isBeliSekarang ? 'var(--orange)' : 'var(--teal)';
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memproses. Silakan coba lagi.');
        activeBtn.disabled = false;
        activeBtn.innerHTML = originalHTML;
    });
}

document.getElementById('btnTambah').addEventListener('click', function() {
    prosesKeranjang(false);
});

document.getElementById('btnBeliSekarang').addEventListener('click', function() {
    prosesKeranjang(true);
});
</script>

</body>
</html>