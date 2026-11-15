<?php
session_start();
include 'includes/config.php';

if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $wa = $_POST['wa'];
    $alamat = $_POST['alamat'];
    
    $stmt = $conn->prepare("SELECT id FROM pelanggan WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $ambil = $stmt->get_result();

    if ($ambil->num_rows == 1) {
        $error = "Email sudah terdaftar!";
    } else {
        $stmt2 = $conn->prepare("INSERT INTO pelanggan (nama_pelanggan, no_whatsapp, alamat_lengkap, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("sssss", $nama, $wa, $alamat, $email, $password);
        
        if ($stmt2->execute()) {
            echo "<script>alert('Pendaftaran Berhasil!'); location='login.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru | Ikan Anyaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --color-teal: #4B9DA9; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .reg-card { background: white; border-radius: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.05); width: 100%; max-width: 550px; overflow: hidden; }
        .reg-header { background-color: var(--color-teal); color: white; padding: 35px 20px; text-align: center; }
        .reg-header h2 { font-weight: 700; font-size: 24px; margin-bottom: 5px; letter-spacing: 1px; }
        .card-body { padding: 40px; }
        .form-label { font-weight: 700; color: #555; font-size: 13px; margin-bottom: 8px; text-transform: uppercase; }
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #eee; background-color: #f9fbfb; font-size: 14px; }
        .form-control:focus { border-color: var(--color-teal); box-shadow: none; background-color: #fff; }
        .btn-daftar { background-color: var(--color-teal); color: white; border-radius: 15px; padding: 15px; width: 100%; font-weight: 700; border: none; transition: 0.3s; }
        .btn-daftar:hover { background-color: #3d808a; transform: translateY(-2px); }
        .login-link a { color: var(--color-teal); text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="reg-card">
    <div class="reg-header">
        <h2>DAFTAR AKUN BARU</h2>
        <p>Gabung bersama penikmat Ikan Anyaran</p>
    </div>
    <div class="card-body">
        <?php if(isset($error)): ?><div class="alert alert-danger py-2 small"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" placeholder="email@anda.com" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">No. WhatsApp</label><input type="text" name="wa" class="form-control" placeholder="0812..." required></div>
            </div>
            <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required></div>
            <div class="mb-4"><label class="form-label">Alamat Lengkap Pengiriman</label><textarea name="alamat" class="form-control" rows="3" placeholder="Nama jalan, Nomor rumah, RT/RW, Desa/Kecamatan" required></textarea></div>
            <button type="submit" name="daftar" class="btn btn-daftar">DAFTAR SEKARANG</button>
            <div class="text-center mt-3 login-link">Sudah punya akun? <a href="login.php">Login disini</a></div>
        </form>
    </div>
</div>
</body>
</html>