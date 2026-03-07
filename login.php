<?php
session_start();
require_once 'database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($role) || empty($username) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND role = ?");
        $stmt->execute([$username, $password, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['staff'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'nama' => $user['nama'],
                'role' => $user['role'],
            ];
            
            // Simpan log login
            simpanLog($user['username'], $user['role'], 'Login', '');
            
            // Redirect ke dashboard sesuai role
            if ($user['role'] === 'operator') {
                header('Location: operator.php');
            } else {
                header('Location: owner.php');
            }
            exit;
        } else {
            $error = 'Username, password, atau role salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff - Penggilingan Padi BangunRejo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <div class="login-page">
        <!-- Logo dan nama -->
        <div style="text-align:center; margin-bottom:30px;">
            <div class="logo-box">🌾</div>
            <h2 style="font-size:20px; font-weight:900; color:#333;">Penggilingan Padi BangunRejo</h2>
            <p style="font-size:13px; color:#888; margin-top:4px;">Sistem Antrian Digital</p>
        </div>

        <!-- Akses Kiosk -->
        <div class="card card-orange" style="margin-bottom:20px; text-align:center;">
            <p style="font-size:14px; opacity:0.9; margin-bottom:12px; font-weight:700;">Akses Kiosk Pelanggan</p>
            <a href="index.php" class="btn btn-white">Mulai Buat Pesanan</a>
        </div>

        <!-- Login Staff -->
        <div class="card">
            <h3 style="font-weight:800; font-size:17px; margin-bottom:4px;">Login Staff</h3>
            <p style="font-size:13px; color:#888; margin-bottom:16px;">Login untuk akses Operator atau Owner</p>

            <?php if ($error): ?>
            <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="">Pilih role</option>
                        <option value="operator" <?= ($_POST['role'] ?? '') === 'operator' ? 'selected' : '' ?>>Operator</option>
                        <option value="owner" <?= ($_POST['role'] ?? '') === 'owner' ? 'selected' : '' ?>>Owner</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username"
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="info-box info-box-blue" style="margin-bottom:16px;">
                    <strong>Demo credentials:</strong><br>
                    Operator: <strong>operator</strong> / <strong>pass123</strong><br>
                    Owner: <strong>owner</strong> / <strong>pass123</strong>
                </div>

                <button type="submit" class="btn btn-orange">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
