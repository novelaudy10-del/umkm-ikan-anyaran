<?php
// Sesuaikan path sesuai struktur folder
include '../includes/config.php'; // pastikan path benar

if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Hapus detail produk pesanan dulu
    $conn->query("DELETE FROM pesanan_produk WHERE id_pesanan='$id'");

    // Hapus data pesanan
    $conn->query("DELETE FROM pesanan WHERE id='$id'");

    header("Location: pesanan.php");
    exit;
}
