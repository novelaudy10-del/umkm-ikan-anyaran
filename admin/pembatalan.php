<?php
include '../includes/config.php';
include 'header_admin.php';

$ambil = $conn->query("
    SELECT pesanan.*, pelanggan.nama_pelanggan 
    FROM pesanan 
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id 
    WHERE pesanan.persetujuan_batal = 'Menunggu'
      AND pesanan.alasan_batal_user IS NOT NULL
      AND pesanan.alasan_batal_user != ''
    ORDER BY pesanan.tanggal_pesan DESC
");

?>

<div class="container mt-4">
    <h3 class="mb-4">Pengajuan Pembatalan Pesanan</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Tanggal Pesanan</th>
                <th>Alasan Pembatalan</th>
                <th width="160">Aksi</th>
            </tr>
        </thead>
        <tbody>

        <?php if ($ambil->num_rows == 0): ?>
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Tidak ada pengajuan pembatalan pesanan
                </td>
            </tr>
        <?php else: ?>

            <?php $no = 1; while ($p = $ambil->fetch_assoc()): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($p['nama_pelanggan']); ?></td>
                <td><?= date('d M Y H:i', strtotime($p['tanggal_pesan'])); ?></td>
                <td>
                    <?= $p['alasan_batal_user'] 
                        ? htmlspecialchars($p['alasan_batal_user']) 
                        : '<span class="text-muted">-</span>'; ?>
                </td>
                <td>
                    <a href="setuju_batal.php?id=<?= $p['id']; ?>" 
                       class="btn btn-success btn-sm"
                       onclick="return confirm('Setujui pembatalan pesanan ini?')">
                        Setuju
                    </a>

                    <a href="tolak_batal.php?id=<?= $p['id']; ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Tolak pembatalan pesanan ini?')">
                        Tolak
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>

        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php include 'footer_admin.php'; ?>
