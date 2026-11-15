<?php
include '../includes/config.php';

$id_produk = mysqli_real_escape_string($conn, $_GET['id']);
$ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = '$id_produk'");
$p = mysqli_fetch_assoc($ambil);

if ($p) {
    $path_foto = "../uploads/" . $p['foto_produk'];
    
    if (file_exists($path_foto) && !empty($p['foto_produk'])) { 
        unlink($path_foto); 
    }

    mysqli_query($conn, "DELETE FROM produk WHERE id_produk = '$id_produk'");
}

echo "<script>alert('Produk telah dihapus'); window.location='produk.php';</script>";
?>

