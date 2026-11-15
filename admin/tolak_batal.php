<?php
include '../includes/config.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("UPDATE pesanan SET persetujuan_batal='Ditolak' WHERE id='$id'");
    echo "<script>alert('Pembatalan ditolak'); location='pembatalan.php';</script>";
}
