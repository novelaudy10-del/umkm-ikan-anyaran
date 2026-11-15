<?php
include '../includes/config.php';


if(isset($_GET['id'])){
    $id = $_GET['id'];
    $conn->query("UPDATE pesanan SET status_pesanan='Proses' WHERE id='$id'");
    echo "<script>alert('Pesanan diterima dan sedang diproses'); location='pesanan.php';</script>";
    exit;
}
