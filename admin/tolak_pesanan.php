<?php
include '../includes/config.php';

if(isset($_POST['id_pesanan'])){
    $id = $_POST['id_pesanan'];
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);

    $conn->query("UPDATE pesanan SET status_pesanan='Dibatalkan', alasan_batal='$alasan' WHERE id='$id'");
    echo "<script>alert('Pesanan telah ditolak'); location='pesanan.php';</script>";
    exit;
}


