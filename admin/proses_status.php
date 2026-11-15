<?php
session_start();
include '../includes/config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $status_valid = ['Proses', 'Selesai'];
    
    if (in_array($status, $status_valid)) {
        $update = $conn->query("UPDATE pesanan SET status_pesanan = '$status' WHERE id = '$id'");

        if ($update) {
            echo "<script>alert('Status pesanan berhasil diperbarui menjadi $status');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui status');</script>";
        }
    }
}

echo "<script>location='pesanan.php';</script>";
?>

