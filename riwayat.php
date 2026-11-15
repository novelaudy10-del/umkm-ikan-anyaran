<?php
session_start();
include 'includes/config.php';

if(!isset($_SESSION['pelanggan'])){
    header("Location: login.php");
    exit;
}

$id_pelanggan = $_SESSION['pelanggan']['id'];

$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_pelanggan=? ORDER BY tanggal_pesan DESC");
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$ambil = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan | Ikan Anyaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --teal: #4B9DA9; 
            --orange: #E37434; 
            --dark-blue: #2c3e50;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f4f7f6;
            background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
            min-height: 100vh;
        }

        .main-card {
            border: none;
            border-radius: 25px;
            background: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .header-line {
            width: 50px; 
            height: 4px; 
            background: var(--teal); 
            margin-top: 10px;
        }

        .table thead {
            background-color: #f8fbfb;
        }

        .table thead th {
            color: var(--teal);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 20px;
            border: none;
        }

        .product-item-box {
            background: #fff9f5;
            border: 1px solid #ffefe5;
            border-radius: 12px;
            padding: 8px 15px;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            font-size: 0.7rem;
            font-weight: 800;
            padding: 6px 15px;
            border-radius: 50px;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .btn-katalog {
            background: var(--teal);
            color: white;
            border-radius: 12px;
            font-weight: 600;
            padding: 10px 25px;
            transition: 0.3s;
            text-decoration: none;
        }

        .btn-katalog:hover {
            background: #3a7d88;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-800 m-0">Riwayat Pesanan</h1>
            <div class="header-line"></div>
            <p class="text-muted mt-3">Pantau daftar transaksi dan status pesanan Anda.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="katalog.php" class="btn-katalog d-inline-block">
                <i class="bi bi-shop me-2"></i>Kembali Ke Katalog
            </a>
        </div>
    </div>

    <div class="main-card shadow">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th width="15%">Waktu</th>
                        <th width="40%" class="text-start">Daftar Belanja</th>
                        <th width="15%">Total Bayar</th>
                        <th width="25%">Status & Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; while($p = $ambil->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #f8f9fa;">
                        <td class="text-center fw-600 text-muted"><?= $no++; ?></td>
                        <td class="text-center">
                            <div class="fw-bold" style="color: var(--dark-blue);"><?= date('d M Y', strtotime($p['tanggal_pesan'])); ?></div>
                            <div class="text-muted small"><?= date('H:i', strtotime($p['tanggal_pesan'])); ?> WIB</div>
                        </td>
                        <td class="py-4">
                            <?php 
                            $id_pesanan = $p['id']; 
                            $stmt_produk = $conn->prepare("SELECT pp.*, p.nama_produk FROM pesanan_produk pp JOIN produk p ON pp.id_produk = p.id_produk WHERE pp.id_pesanan = ?");
                            $stmt_produk->bind_param("i", $id_pesanan);
                            $stmt_produk->execute();
                            $ambil_produk = $stmt_produk->get_result();
                            while($produk = $ambil_produk->fetch_assoc()):
                            ?>
                                <div class="product-item-box">
                                    <span class="product-name" style="font-size: 0.85rem; font-weight: 600;">
                                        <i class="bi bi-check2-circle text-success me-2"></i><?= htmlspecialchars($produk['nama_produk'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <span class="badge bg-teal" style="background: var(--teal); font-size: 0.7rem;">x<?= $produk['jumlah']; ?></span>
                                </div>
                            <?php endwhile; $stmt_produk->close(); ?>
                        </td>
                        <td class="text-center">
                            <span class="fw-800 text-orange" style="color: var(--orange);">Rp <?= number_format($p['total_bayar']); ?></span>
                        </td>
                        <td class="text-center">
                            <?php 
                            $st = $p['status_pesanan']; 
                            $batal = $p['persetujuan_batal']; 
                            $alasan_batal = $p['alasan_batal_user'];
                            $status_text = strtoupper($st);
                            if($st == 'Baru') { 
                                $badge = 'bg-warning text-dark'; 
                            } elseif($st == 'Dibatalkan') { 
                                $badge = 'bg-danger text-white'; 
                            } elseif($st == 'Proses') { 
                                $badge = 'bg-info text-white'; 
                                $status_text = "PESANAN DIPROSES";
                            } else { 
                                $badge = 'bg-success text-white'; 
                            }
                            ?>
                            
                            <span class="status-badge <?= $badge; ?> shadow-sm mb-1">
                                <?= htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'); ?>
                            </span>

                            <div class="mt-2">
                                <?php 
                                if($batal == 'Ditolak'): ?>
                                    <div class="text-danger small fw-bold" style="font-size: 0.65rem;">
                                        <i class="bi bi-x-octagon"></i> Pembatalan Ditolak Admin
                                    </div>

                                <?php elseif($st == 'Baru' && $batal == 'Menunggu' && !empty($alasan_batal)): ?>
                                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">
                                        <i class="bi bi-hourglass-split"></i> Menunggu Persetujuan Batal
                                    </span>

                                <?php 
                                elseif($st == 'Baru'): ?>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            style="font-size: 0.7rem; border-radius: 8px;"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalBatal" 
                                            onclick="setBatalID('<?= htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?>')">
                                        <i class="bi bi-x-circle"></i> Batalkan
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBatal" tabindex="-1" aria-labelledby="modalBatalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <form action="proses_batal.php" method="POST">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-800" id="modalBatalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <input type="hidden" name="id_pesanan" id="id_pesanan_batal">
                    <p class="text-muted small">Apakah Anda yakin ingin membatalkan pesanan ini? Pengajuan akan dikirim ke admin untuk disetujui.</p>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Alasan Membatalkan:</label>
                        <select class="form-select" name="alasan_user" required style="border-radius: 10px;">
                            <option value="">-- Pilih Alasan --</option>
                            <option value="Ingin mengubah rincian pesanan">Ingin mengubah rincian pesanan</option>
                            <option value="Salah memasukkan alamat">Salah memasukkan alamat</option>
                            <option value="Menemukan harga lebih murah">Menemukan harga lebih murah</option>
                            <option value="Berubah pikiran / Tidak jadi beli">Berubah pikiran / Tidak jadi beli</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light fw-600" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                    <button type="submit" name="ajukan_batal" class="btn btn-danger fw-600" style="border-radius: 10px;">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function setBatalID(id) {
        document.getElementById('id_pesanan_batal').value = id;
    }
</script>

</body>
</html>