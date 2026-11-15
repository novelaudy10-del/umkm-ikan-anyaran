<?php include 'header_admin.php'; ?>

<style>
    body {
        background-color: #f4f7f6;
        background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
    }
</style>

<div class="container mt-4">
    <h2 class="fw-bold mb-4" style="color: #4B9DA9;">Tambah Produk Baru</h2>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Ikan Pari" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori Ikan</label>
                    <select name="jenis" class="form-control" required>
                        <option value="Ikan Laut">Ikan Laut</option>
                        <option value="Ikan Tawar">Ikan Tawar</option>
                        <option value="Ikan Olahan">Ikan Olahan</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light mb-3">
                            <h6 class="fw-bold text-success">Satuan Eceran</h6>
                            <div class="mb-2">
                                <label class="small fw-bold">Harga Eceran (Rp)</label>
                                <input type="number" name="harga_eceran" class="form-control" placeholder="0" required>
                            </div>
                            <div>
                                <label class="small fw-bold">Stok Eceran</label>
                                <input type="number" name="stok_eceran" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded bg-light mb-3">
                            <h6 class="fw-bold text-success">Satuan Per Ikat</h6>
                            <div class="mb-2">
                                <label class="small fw-bold">Harga Per Ikat (Rp)</label>
                                <input type="number" name="harga_ikat" class="form-control" placeholder="0" required>
                            </div>
                            <div>
                                <label class="small fw-bold">Stok Ikat</label>
                                <input type="number" name="stok_ikat" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Foto Produk Utama</label>
                    <input type="file" name="foto" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Foto Detail (Opsional)</label>
                    <input type="file" name="foto2" class="form-control">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="simpan" class="btn btn-primary px-4" style="background-color: #4B9DA9; border: none;">
                        <i class="bi bi-save me-2"></i>Simpan Produk
                    </button>
                    <a href="produk.php" class="btn btn-secondary px-4">Batal</a>
                </div>
            </form>

            <?php
            if (isset($_POST['simpan'])) {
                $nama_produk = $_POST['nama'];
                $jenis       = $_POST['jenis'];
                $h_eceran    = $_POST['harga_eceran'];
                $h_ikat      = $_POST['harga_ikat'];
                $s_eceran    = $_POST['stok_eceran'];
                $s_ikat      = $_POST['stok_ikat'];

                $foto1 = $_FILES['foto']['name'];
                $tmp1  = $_FILES['foto']['tmp_name'];
                move_uploaded_file($tmp1, "../uploads/" . $foto1);

                $foto2 = "";
                if (!empty($_FILES['foto2']['name'])) {
                    $foto2 = $_FILES['foto2']['name'];
                    $tmp2  = $_FILES['foto2']['tmp_name'];
                    move_uploaded_file($tmp2, "../uploads/" . $foto2);
                }

                $query = "INSERT INTO produk (nama_produk, jenis_ikan, harga_eceran, harga_ikat, stok_ikat, stok_eceran, foto_produk, foto_produk2) 
                          VALUES ('$nama_produk', '$jenis', '$h_eceran', '$h_ikat', '$s_ikat', '$s_eceran', '$foto1', '$foto2')";
                
                $simpan = mysqli_query($conn, $query);

                if ($simpan) {
                    echo "<script>alert('Produk berhasil ditambahkan!'); location='produk.php';</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Gagal: " . mysqli_error($conn) . "</div>";
                }
            }
            ?>
        </div>
    </div>
</div>

<?php include 'footer_admin.php'; ?>