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

    /* Container Tabel */
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

    h1, h2, h3, h5, strong, i { color: var(--text-dark) !important; }
    .text-muted { color: var(--text-muted) !important; }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        border: none;
    }
    .bg-baru { background-color: var(--color-4); color: white !important; }
    .bg-proses { background-color: var(--color-2); color: white !important; }
    .bg-selesai { background-color: var(--color-1); color: var(--text-dark) !important; }
    .bg-batal { background-color: #eecaca; color: #a33333 !important; }

    /* Modal Styling */
    .modal-content { border-radius: 20px; border: none; }
    .modal-header { background-color: var(--color-4); color: white; border-top-left-radius: 20px; border-top-right-radius: 20px; }

    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }

    .btn-action-custom {
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
    }
</style>

<div class="main-content">
    <div class="container-fluid px-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--color-2) !important;">Daftar Pesanan Masuk</h2>
                <p class="text-muted small">Pantau dan proses pesanan dari pelanggan secara real-time.</p>
            </div>
            <div class="p-2 px-3 rounded-3 shadow-sm" style="background-color: var(--color-3); border: 1px solid var(--color-1);">
                <span class="fw-bold small">Total: <?php echo $conn->query("SELECT id FROM pesanan")->num_rows; ?> Pesanan</span>
            </div>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Pelanggan</th>
                            <th>Waktu Pesan</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Persetujuan Batal</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $ambil = $conn->query("
                            SELECT pesanan.*, pelanggan.nama_pelanggan, pelanggan.no_whatsapp
                            FROM pesanan
                            JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
                            ORDER BY pesanan.tanggal_pesan DESC
                        ");

                        while($p = $ambil->fetch_assoc()):
                            $status = $p['status_pesanan'];
                            $persetujuan_batal = $p['persetujuan_batal']; 
                            $alasan_user = $p['alasan_batal_user'];
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                            <td>
                                <div class="fw-bold mb-0"><?= htmlspecialchars($p['nama_pelanggan']); ?></div>
                                <a href="https://wa.me/<?= $p['no_whatsapp']; ?>" target="_blank" class="text-decoration-none small fw-bold" style="color: var(--color-2) !important;">
                                    <i class="bi bi-whatsapp"></i> <?= htmlspecialchars($p['no_whatsapp']); ?>
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold small"><?= date('d M Y', strtotime($p['tanggal_pesan'])); ?></div>
                                <div class="text-muted small" style="font-size: 0.7rem;"><?= date('H:i', strtotime($p['tanggal_pesan'])); ?> WIB</div>
                            </td>
                            <td class="fw-bold" style="color: var(--color-2) !important;">
                                Rp <?= number_format($p['total_bayar']); ?>
                            </td>
                            <td>
                                <?php 
                                if($status == 'Baru') echo '<span class="badge-custom bg-baru">Baru</span>';
                                elseif($status == 'Proses') echo '<span class="badge-custom bg-proses text-white">Dikirim</span>';
                                elseif($status == 'Selesai') echo '<span class="badge-custom bg-selesai">Selesai</span>';
                                elseif($status == 'Dibatalkan') echo '<span class="badge-custom bg-batal">Batal</span>';
                                ?>
                            </td>
                            <td>
                                <?php 
                                if(!empty($alasan_user) && $persetujuan_batal == 'Menunggu' && $status != 'Dibatalkan'){
                                    echo '<span class="badge bg-baru animate-pulse small p-1 px-2 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i>Minta Batal</span>';
                                } elseif($persetujuan_batal == 'Disetujui') {
                                    echo '<span class="text-success small fw-bold"><i class="bi bi-check-circle"></i> Disetujui</span>';
                                } elseif($persetujuan_batal == 'Ditolak') {
                                    echo '<span class="text-danger small fw-bold"><i class="bi bi-x-circle"></i> Ditolak</span>';
                                } else {
                                    echo '<span class="text-muted small">-</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group gap-1">
                                    <a href="detail_pesanan.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-action-custom border" style="background-color: #f8f9fa;">
                                        <i class="bi bi-eye text-info"></i>
                                    </a>

                                    <?php if($status != 'Selesai' && $status != 'Dibatalkan'): ?>
                                        <?php if(empty($alasan_user) || $persetujuan_batal == 'Ditolak'): ?>
                                            
                                            <?php if($status == 'Baru'): ?>
                                                <a href="terima_pesanan.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-action-custom text-white" style="background-color: var(--color-1); color: var(--text-dark) !important;">Terima</a>
                                                
                                                <button class="btn btn-sm btn-action-custom text-white" style="background-color: var(--color-4);" onclick="tolakPesanan('<?= $p['id']; ?>')">Tolak</button>
                                            <?php endif; ?>

                                            <?php if($status == 'Proses'): ?>
                                                <a href="selesai_pesanan.php?id=<?= $p['id']; ?>" 
                                                   class="btn btn-sm btn-action-custom text-white" style="background-color: var(--color-2);"
                                                   onclick="return confirm('Tandai pesanan ini sebagai Selesai?')">
                                                   <i class="bi bi-check-all"></i> Selesaikan
                                                </a>
                                            <?php endif; ?>

                                        <?php elseif($persetujuan_batal == 'Menunggu'): ?>
                                            <a href="pembatalan.php" class="btn btn-sm btn-action-custom bg-baru">Proses Batal</a>
                                        <?php endif; ?>
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
</div>

<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="tolak_pesanan.php" method="POST" class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-white">Tolak Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_pesanan" id="id_tolak">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alasan Penolakan</label>
                    <textarea name="alasan" class="form-control border-2" style="border-color: var(--color-3);" required placeholder="Contoh: Stok ikan habis atau toko sedang tutup..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                <button type="submit" class="btn text-white border-0" style="background-color: var(--color-4); border-radius: 10px;">Kirim Penolakan</button>
            </div>
        </form>
    </div>
</div>

<script>
function tolakPesanan(id){
    document.getElementById('id_tolak').value = id;
    new bootstrap.Modal(document.getElementById('modalTolak')).show();
}
</script>

<?php include 'footer_admin.php'; ?>