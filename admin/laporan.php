<?php 
include 'header_admin.php'; 

$tgl_pilihan = isset($_GET['tgl']) ? mysqli_real_escape_string($conn, $_GET['tgl']) : '';
$bln_pilihan = isset($_GET['bln']) ? mysqli_real_escape_string($conn, $_GET['bln']) : '';
$thn_pilihan = isset($_GET['thn']) ? mysqli_real_escape_string($conn, $_GET['thn']) : '';

$query = "SELECT pesanan.*, pelanggan.nama_pelanggan
          FROM pesanan 
          JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id 
          WHERE pesanan.status_pesanan = 'Selesai'";

$judul_laporan = "Seluruh Penjualan";

// Logika Filter
if (!empty($tgl_pilihan)) {
    $query .= " AND DATE(tanggal_pesan) = '$tgl_pilihan'";
    $judul_laporan = "Laporan Tanggal: " . date('d M Y', strtotime($tgl_pilihan));
} elseif (!empty($bln_pilihan) && !empty($thn_pilihan)) {
    $query .= " AND MONTH(tanggal_pesan) = '$bln_pilihan' AND YEAR(tanggal_pesan) = '$thn_pilihan'";
    $nama_bulan = date('F', mktime(0, 0, 0, $bln_pilihan, 10));
    $judul_laporan = "Laporan Bulan: $nama_bulan $thn_pilihan";
} elseif (!empty($thn_pilihan)) {
    $query .= " AND YEAR(tanggal_pesan) = '$thn_pilihan'";
    $judul_laporan = "Laporan Tahun: $thn_pilihan";
}

$query .= " ORDER BY tanggal_pesan DESC";
$ambil = mysqli_query($conn, $query);
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

    .table td, .table th { padding: 1rem 0.75rem !important; vertical-align: middle; }

    .table-container { 
        background: white; 
        border-radius: 20px; 
        box-shadow: 0 10px 30px rgba(75, 157, 169, 0.1); 
        overflow: hidden; 
        border: 2px solid var(--color-3);
    }

    .table thead { background-color: var(--color-2); }
    .table th { border: none; color: white !important; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; }
    .table td { border-bottom: 1px solid #f0f4f4; color: var(--text-dark) !important; }

    .form-label { font-weight: 700; font-size: 0.85rem; color: var(--text-dark); }
    .form-control, .form-select { border-radius: 8px; border: 1px solid var(--color-1); color: var(--text-dark); }

    h2, h6, i, strong { color: var(--text-dark) !important; }
    .text-muted { color: var(--text-muted) !important; }

    @media print {
        .no-print, .navbar, .btn, .card-header-filter, .card-filter { display: none !important; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; }
        .table-container { border: none !important; box-shadow: none !important; }
        body { background-color: white !important; background-image: none !important; }
    }
</style>

<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h6 class="text-uppercase fw-bold mb-1" style="color: var(--color-4) !important; letter-spacing: 2px;">Data Keuangan</h6>
            <h2 class="fw-bold mb-0" style="color: var(--color-2) !important;"><?= $judul_laporan; ?></h2>
        </div>
        <div class="col-auto no-print">
            <button onclick="window.print()" class="btn shadow-sm px-4" style="background-color: var(--color-2); color: var(--color-3); border-radius: 12px; font-weight: 600; border: none;">
                <i class="bi bi-printer-fill me-2" style="color: var(--color-3) !important;"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 no-print card-filter" style="border-radius: 15px; border-left: 5px solid var(--color-2) !important;">
        <div class="card-body p-4">
            <form action="laporan.php" method="get" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Spesifik</label>
                    <input type="date" name="tgl" class="form-control" value="<?= $tgl_pilihan; ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bln" class="form-select">
                        <option value="">-- Semua Bulan --</option>
                        <?php
                        $bulanArr = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        foreach ($bulanArr as $i => $nama) {
                            $val = $i + 1; $sel = ($bln_pilihan == $val) ? 'selected' : '';
                            echo "<option value='$val' $sel>$nama</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="thn" class="form-select">
                        <option value="">-- Tahun --</option>
                        <?php
                        $thn_skrg = date('Y');
                        for ($y = $thn_skrg; $y >= 2023; $y--) {
                            $sel = ($thn_pilihan == $y) ? 'selected' : '';
                            echo "<option value='$y' $sel>$y</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn text-white w-100" style="border-radius: 8px; background-color: var(--color-2); border: none; font-weight: 600;">
                        <i class="bi bi-search me-2 text-white"></i>Filter Data
                    </button>
                    <a href="laporan.php" class="btn btn-outline-secondary w-100" style="border-radius: 8px;">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="text-center">
                        <th style="width: 70px;">NO</th>
                        <th class="text-start">PELANGGAN</th>
                        <th>TANGGAL</th>
                        <th class="text-end pe-4">TOTAL BAYAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $nomor = 1; 
                    $total_semua = 0;
                    while($pecah = mysqli_fetch_assoc($ambil)): 
                        $subtotal = $pecah['total_bayar']; 
                        $total_semua += $subtotal;
                    ?>
                    <tr>
                        <td class="text-center text-muted fw-bold"><?= $nomor++; ?></td>
                        <td>
                            <div class="fw-bold" style="color: var(--color-2) !important;"><?= $pecah['nama_pelanggan']; ?></div>
                            <small class="text-muted text-uppercase">ID Transaksi: #<?= $pecah['id']; ?></small>
                        </td>
                        <td class="text-center fw-bold">
                            <?= date('d/m/Y', strtotime($pecah['tanggal_pesan'])); ?>
                        </td>
                        <td class="text-end pe-4 fw-bold" style="color: var(--text-dark) !important;">
                            Rp <?= number_format($subtotal); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if (mysqli_num_rows($ambil) == 0): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted italic">Tidak ada data penjualan untuk periode ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot style="border-top: 3px solid var(--color-2);">
                    <tr style="background-color: var(--color-3);">
                        <td colspan="3" class="text-end py-4 fw-bold" style="font-size: 1.1rem;">TOTAL PENDAPATAN :</td>
                        <td class="text-end pe-4 py-4 fw-bold" style="color: var(--color-4) !important; font-size: 1.4rem;">
                            Rp <?= number_format($total_semua); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>

