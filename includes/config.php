<?php
date_default_timezone_set('Asia/Jakarta');

$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['DB_HOST'] ?? 'localhost';
$user = $env['DB_USER'] ?? 'root';
$pass = $env['DB_PASS'] ?? '';
$db   = $env['DB_NAME'] ?? 'db_ikan_anyaran';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

mysqli_query($conn, "SET time_zone = '+07:00'");
?>