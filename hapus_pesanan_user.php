<?php
session_start();
include 'includes/config.php';

if(!isset($_SESSION['pelanggan'])){
    header("Location: login.php");
    exit;
}

$id_pelanggan = $_SESSION['pelanggan']['id'];

if(isset($_GET['id'])){
    $id_pesanan = $_GET['id'];
    $cek = $conn->query("SELECT * FROM pesanan WHERE id='$id_pesanan' AND id_pelanggan='$id_pelanggan'");
    
    if($cek->num_rows > 0){
        $conn->query("DELETE FROM pesanan_produk WHERE id_pesanan='$id_pesanan'");
        $conn->query("DELETE FROM pesanan WHERE id='$id_pesanan'");
        
        echo "<script>alert('Pesanan berhasil dihapus'); location='riwayat.php';</script>";
        exit;
    } else {
        echo "<script>alert('Pesanan tidak bisa dihapus'); location='riwayat.php';</script>";
        exit;
    }
}
