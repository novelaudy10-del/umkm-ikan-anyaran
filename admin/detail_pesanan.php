<?php
include '../includes/config.php';
include 'header_admin.php';

$id = $_GET['id'];

$pesanan = $conn->query("
    SELECT pesanan.*, pelanggan.nama_pelanggan, pelanggan.no_whatsapp
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
    WHERE pesanan.id = '$id'
")->fetch_assoc();

$produk = $conn->query("
    SELECT produk.nama_produk, pesanan_produk.*
    FROM pesanan_produk
    JOIN produk ON pesanan_produk.id_produk = produk.id_produk
    WHERE pesanan_produk.id_pesanan = '$id'
");
?>

<style>
    :root {
        --color-1: #91C6BC;
        --color-2: #4B9DA9;
        --color-3: #F6F3C2;
        --color-4: #E37434;
        --text-dark: #1a4a52;
    }

    body {
        background-color: #f4f7f6;
        background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
        color: var(--text-dark);
    }

    .main-content { min-height: 100vh; padding: 40px 0; }

    .card-custom {
        background: white;
        border-radius: 20px;
        border: 2px solid var(--color-3);
        box-shadow: 0 10px 30px rgba(75, 157, 169, 0.1);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .card-header-custom {
        background-color: var(--color-2);
        color: white;
        padding: 15px 25px;
        font-weight: 600;
        border: none;
    }

    .table thead {
        background-color: #f8fbfb;
    }

    .table th {
        color: var(--color-2);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        padding: 15px;
    }

    .badge-status {
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .info-label {
        font-weight: bold;
        color: var(--text-dark);
        width: 150px;
        display: inline-block;
    }
</style>

<div class="main-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="color: var(--color-2) !important;">
                <i class="bi bi-receipt me-2"></i>Detail Pesanan #<?= $id; ?>
            </h2>
            <a href="pesanan.php" class="btn btn-outline-secondary btn-sm" style="border-radius: 10px;">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card-custom">
                    <div class="card-header-custom">Informasi Pesanan</div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p><span class="info-label">Nama:</span> <?= htmlspecialchars($pesanan['nama_pelanggan']); ?></p>
                                <p><span class="info-label">WhatsApp:</span> 
                                    <a href="https://wa.me/<?= $pesanan['no_whatsapp']; ?>" target="_blank" class="text-decoration-none fw-bold" style="color: var(--color-2);">
                                        <?= htmlspecialchars($pesanan['no_whatsapp']); ?>
                                    </a>
                                </p>
                                <p><span class="info-label">Tanggal:</span> <?= date('d M Y H:i', strtotime($pesanan['tanggal_pesan'])); ?> WIB</p>
                            </div>
                            <div class="col-md-6">
                                <p><span class="info-label">Status:</span> 
                                    <?php 
                                        $status = $pesanan['status_pesanan'];
                                        if($status == 'Baru') echo '<span class="badge bg-warning text-dark badge-status">BARU</span>';
                                        elseif($status == 'Proses') echo '<span class="badge bg-info text-white badge-status">DIKIRIM / PROSES</span>';
                                        elseif($status == 'Selesai') echo '<span class="badge bg-success badge-status">SELESAI</span>';
                                        elseif($status == 'Dibatalkan') echo '<span class="badge bg-danger badge-status">DIBATALKAN</span>';
                                    ?>
                                </p>

                                <?php 
                                // Memperbaiki Error Undefined Key "alasan_tolak" dengan pengecekan isset
                                if($status == 'Dibatalkan'): 
                                    // Gunakan 'alasan_batal' sesuai kolom di database Anda
                                    $alasan = isset($pesanan['alasan_batal']) ? $pesanan['alasan_batal'] : (isset($pesanan['alasan_batal_user']) ? $pesanan['alasan_batal_user'] : 'Tanpa Alasan');
                                ?>
                                    <div class="p-3 rounded-3" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                        <strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i> Alasan Dibatalkan:</strong>
                                        <p class="mb-0 text-muted small mt-1"><?= htmlspecialchars($alasan); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card-custom">
                    <div class="card-header-custom">Rincian Produk</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while($p = $produk->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= htmlspecialchars($p['nama_produk']); ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($p['satuan']); ?></span></td>
                                        <td class="text-center"><?= $p['jumlah']; ?></td>
                                        <td class="text-end">Rp <?= number_format($p['harga_satuan'],0,',','.'); ?></td>
                                        <td class="text-end pe-4 fw-bold" style="color: var(--color-2);">Rp <?= number_format($p['harga_satuan'] * $p['jumlah'],0,',','.'); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: var(--color-3);">
                                        <td colspan="4" class="text-end fw-bold py-3">TOTAL BAYAR</td>
                                        <td class="text-end pe-4 fw-bold py-3" style="color: var(--color-4); font-size: 1.1rem;">
                                            Rp <?= number_format($pesanan['total_bayar'],0,',','.'); ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>