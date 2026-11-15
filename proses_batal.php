<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['pelanggan'])) {
    echo "<script>location='login.php';</script>";
    exit;
}

if (isset($_POST['ajukan_batal'])) {
    $id_pesanan = mysqli_real_escape_string($conn, $_POST['id_pesanan']);
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan_user']);
    $id_pelanggan = $_SESSION['pelanggan']['id'];

    $cek = $conn->query("SELECT * FROM pesanan WHERE id='$id_pesanan' AND id_pelanggan='$id_pelanggan'");
    $data = $cek->fetch_assoc();

    if ($data) {
        $update = $conn->query("UPDATE pesanan SET 
            alasan_batal_user = '$alasan', 
            persetujuan_batal = 'Menunggu' 
            WHERE id = '$id_pesanan'");

        if ($update) {
            echo "<script>alert('Pengajuan pembatalan berhasil dikirim!'); location='riwayat.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim pengajuan.'); location='riwayat.php';</script>";
        }
    } else {
        echo "<script>alert('Pesanan tidak valid!'); location='riwayat.php';</script>";
    }
} else {
    echo "<script>location='riwayat.php';</script>";
}
?>