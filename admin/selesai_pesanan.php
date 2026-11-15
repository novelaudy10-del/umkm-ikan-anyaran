<?php
include '../includes/config.php';

$id_pesanan = $_GET['id'];
$conn->query("UPDATE pesanan SET status_pesanan='Selesai' WHERE id='$id_pesanan'");

echo "<script>alert('Pesanan telah diselesaikan');</script>";
echo "<script>location='pesanan.php';</script>";
?>