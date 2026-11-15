<?php 
include 'header_admin.php'; 

$id_produk = $_GET['id'];
$ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_produk'");
$p = mysqli_fetch_assoc($ambil);

if (!$p) {
    echo "<script>alert('Data tidak ditemukan!'); location='produk.php';</script>";
}
?>

<style>
    body {
        background-color: #f4f7f6;
        background-image: url("https://www.transparenttextures.com/patterns/cream-paper.png");
    }

    .btn-tema {
        background-color: #4B9DA9 !important;
        border-color: #4B9DA9 !important;
        color: white !important;
        transition: 0.3s;
    }

    .btn-tema:hover {
        background-color: #387d87 !important;
        transform: translateY(-2px);
    }

    .text-tema {
        color: #4B9DA9 !important;
    }
</style>

<div class="container py-5">
    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-header bg-white py-3">
            <h3 class="fw-bold mb-0 text-tema">Edit Stok</h3>
        </div>
        <div class="card-body p-4">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="nama" value="<?= $p['nama_produk'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Jenis Ikan</label>
                    <select name="jenis" class="form-control" required>
                        <option value="Ikan Laut" <?= ($p['jenis_ikan'] == 'Ikan Laut') ? 'selected' : '' ?>>Ikan Laut</option>
                        <option value="Ikan Air Tawar" <?= ($p['jenis_ikan'] == 'Ikan Air Tawar') ? 'selected' : '' ?>>Ikan Air Tawar</option>
                        <option value="Ikan Olahan" <?= ($p['jenis_ikan'] == 'Ikan Olahan') ? 'selected' : '' ?>>Ikan Olahan</option>
                    </select>
                </div>

                <div class="row bg-light p-3 rounded mb-3">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-tema">SATUAN IKAT</h6>
                        <label class="small">Harga per Ikat</label>
                        <input type="number" name="harga_ikat" value="<?= $p['harga_ikat'] ?>" class="form-control mb-2" required>
                        <label class="small">Stok Ikat</label>
                        <input type="number" name="stok_ikat" value="<?= $p['stok_ikat'] ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-tema">SATUAN ECERAN</h6>
                        <label class="small">Harga Eceran</label>
                        <input type="number" name="harga_eceran" value="<?= $p['harga_eceran'] ?>" class="form-control mb-2" required>
                        <label class="small">Stok Eceran</label>
                        <input type="number" name="stok_eceran" value="<?= $p['stok_eceran'] ?>" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Foto Utama</label><br>
                        <img src="../uploads/<?= $p['foto_produk'] ?>" width="80" class="rounded border mb-2">
                        <input type="file" name="foto" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Foto Detail</label><br>
                        <img src="../uploads/<?= $p['foto_produk2'] ?>" width="80" class="rounded border mb-2">
                        <input type="file" name="foto2" class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button name="edit" class="btn btn-tema px-5">Simpan Perubahan</button>
                    <a href="produk.php" class="btn btn-light px-4">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if(isset($_POST['edit'])){
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $h_eceran = $_POST['harga_eceran'];
    $h_ikat = $_POST['harga_ikat'];
    $s_ikat = $_POST['stok_ikat'];
    $s_eceran = $_POST['stok_eceran'];
    
    $update_fields = [
        "nama_produk='$nama'",
        "jenis_ikan='$jenis'",
        "harga_eceran='$h_eceran'",
        "harga_ikat='$h_ikat'",
        "stok_ikat='$s_ikat'",
        "stok_eceran='$s_eceran'"
    ];

    if(!empty($_FILES['foto']['name'])){
        $namafoto = $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/".$namafoto);
        $update_fields[] = "foto_produk='$namafoto'";
    }

    if(!empty($_FILES['foto2']['name'])){
        $namafoto2 = $_FILES['foto2']['name'];
        move_uploaded_file($_FILES['foto2']['tmp_name'], "../uploads/".$namafoto2);
        $update_fields[] = "foto_produk2='$namafoto2'";
    }

    $query_string = implode(", ", $update_fields);
    $query = "UPDATE produk SET $query_string WHERE id_produk='$id_produk'";
    
    mysqli_query($conn, $query);
    echo "<script>alert('Data Berhasil Diperbarui!'); location='produk.php';</script>";
}
include 'footer_admin.php';
?>