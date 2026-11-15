<?php
session_start();
include '../includes/config.php';

if (isset($_SESSION['admin'])) { 
    header("Location: dashboard.php"); 
    exit; 
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $ambil_admin = $stmt->get_result();
    
    if ($ambil_admin->num_rows == 1) {
        $admin = $ambil_admin->fetch_assoc();
        
        // Cek password: bcrypt dulu, lalu fallback ke md5 (migrasi transparan)
        $valid = false;
        if (password_verify($password, $admin['password'])) {
            $valid = true;
        } elseif (md5($password) === $admin['password']) {
            // Migrasi transparan: upgrade hash md5 lama ke bcrypt
            $newHash = password_hash($password, PASSWORD_BCRYPT);
            $upd = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $upd->bind_param("si", $newHash, $admin['id']);
            $upd->execute();
            $valid = true;
        }
        
        if ($valid) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $admin;
            $sukses = "Login Admin Berhasil! Mengalihkan...";
            header("refresh:1;url=dashboard.php");
        } else {
            $error = "Username atau Password Admin salah!";
        }
    } else {
        $error = "Username atau Password Admin salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Ikan Anyaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --color-1: #91C6BC;
            --color-2: #4B9DA9;
            --color-3: #F6F3C2;
            --color-4: #E37434;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--color-2) 0%, var(--color-1) 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center; margin: 0;
        }

        .login-card {
            border: none; border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden; background: white;
            width: 100%; max-width: 420px;
        }

        .login-header {
            background-color: var(--color-3);
            padding: 40px 30px 55px 30px;
            position: relative;
        }

        .brand-wrapper {
            display: flex; align-items: center; justify-content: center;
            gap: 15px; position: relative; z-index: 2;
        }

        .icon-box-small {
            width: 50px; height: 50px;
            background: white; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        }

        .login-header h3 {
            font-weight: 800; color: var(--color-2);
            margin: 0; letter-spacing: -1px;
        }

        .wave-container {
            position: absolute; bottom: 0; left: 0; width: 100%; line-height: 0;
        }
        .wave-container svg { height: 40px; width: 100%; }
        .wave-container .shape-fill { fill: #FFFFFF; }

        .form-control { border-radius: 12px; padding: 12px 15px; border: 2px solid #eee; background-color: #f9f9f9; }
        .form-control:focus { border-color: var(--color-1); box-shadow: none; background-color: #fff; }

        .btn-login { 
            background: var(--color-4); border: none; padding: 14px; 
            border-radius: 12px; font-weight: 700; color: white; 
            transition: 0.3s; width: 100%; 
        }
        .btn-login:hover { background: #c55a1d; transform: translateY(-2px); color: white; }
        
        .card-body { padding: 40px 30px; }
    </style>
</head>
<body>

<div class="container p-3">
    <div class="login-card mx-auto">
        <div class="login-header text-center">
            <div class="brand-wrapper">
                <div class="icon-box-small">
                    <i class="bi bi-shield-lock-fill" style="color: var(--color-2);"></i>
                </div>
                <h3>ADMIN <span style="color: var(--color-4);">PANEL</span></h3>
            </div>
            <p class="text-muted small mt-2 position-relative" style="z-index:2;">Silakan masuk untuk mengelola toko</p>
            
            <div class="wave-container">
                <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                    <path d="M0.00,49.98 C149.99,150.00 349.89,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" class="shape-fill"></path>
                </svg>
            </div>
        </div>
        
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger border-0 small py-2 text-center" style="border-radius: 10px;"><?= $error ?></div>
            <?php endif; ?>
            <?php if(isset($sukses)): ?>
                <div class="alert alert-success border-0 small py-2 text-center" style="border-radius: 10px;"><?= $sukses ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Username Admin</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="login" class="btn btn-login mb-2">LOGIN KE DASHBOARD</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>