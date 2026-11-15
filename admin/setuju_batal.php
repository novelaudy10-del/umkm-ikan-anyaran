<?php
include '../includes/config.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("UPDATE pesanan SET persetujuan_batal='Disetujui', status_pesanan='Dibatalkan' WHERE id='$id'");
    echo "<script>alert('Pembatalan disetujui'); location='pembatalan.php';</script>";
}
