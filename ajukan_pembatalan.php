<?php
session_start();
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_pesanan'];
    $alasan = $_POST['alasan_user'] . " (" . $_POST['detail_alasan'] . ")";

    $conn->query("UPDATE pesanan SET 
                  persetujuan_batal = 'Menunggu', 
                  alasan_batal_user = '$alasan' 
                  WHERE id = '$id'");

    echo "<script>alert('Pengajuan pembatalan telah dikirim ke admin.');</script>";
    echo "<script>location='riwayat.php';</script>";
}
?>