<?php
session_start();
require_once 'database.php';

// Cek session
if (empty($_SESSION['pesanan_baru'])) {
    header('Location: buat_pesanan.php');
    exit;
}

$pesanan = $_SESSION['pesanan_baru'];
$error = '';

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $berat = floatval($_POST['berat'] ?? 0);
    
    if ($berat <= 0) {
        $error = 'Berat padi harus lebih dari 0 kg!';
    } else {
        $_SESSION['pesanan_baru']['berat'] = $berat;
        header('Location: pembayaran.php');
        exit;
    }
}

$berat_val = $_SESSION['pesanan_baru']['berat'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penimbangan Padi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <div class="top-bar">
        <a href="buat_pesanan.php" class="back-btn">← Kembali</a>
        <h1>⚖️ Penimbangan Padi</h1>
        <div class="subtitle" style="font-size:12px; opacity:0.8; margin-top:4px;">ID: <?= htmlspecialchars($_SESSION['pesanan_baru']['order_id'] ?? 'Belum dibuat') ?></div>
    </div>

    <div class="content">
        <?php if ($error): ?>
        <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Info Pelanggan -->
        <div class="card" style="margin-bottom:14px;">
            <div class="detail-row">
                <span class="label">👤 Nama Pelanggan</span>
                <span class="value"><?= htmlspecialchars($pesanan['nama']) ?></span>
            </div>
            <div class="detail-row">
                <span class="label">📞 Nomor Telepon</span>
                <span class="value"><?= htmlspecialchars($pesanan['telepon']) ?></span>
            </div>
        </div>

        <!-- Form Berat -->
        <div class="card">
            <form method="POST" id="formBerat">
                <div class="form-group">
                    <label>Berat Padi (kg)</label>
                    <div class="input-row">
                        <input type="number" name="berat" id="inputBerat" 
                               placeholder="0" step="0.1" min="0.1"
                               value="<?= $berat_val ?>"
                               oninput="updateDisplay(this.value)"
                               required>
                        <button type="button" class="btn btn-gray" onclick="simulasiTimbang()" style="border-radius:12px;">
                            ⚡ Timbang Otomatis
                        </button>
                    </div>
                </div>

                <!-- Display berat -->
                <div class="weight-display" id="weightDisplay">
                    <div class="weight-num" id="weightNum"><?= $berat_val ?: '0' ?></div>
                    <div class="weight-unit">kg</div>
                </div>

                <button type="submit" class="btn btn-orange" style="margin-top:16px;">
                    Lanjut ke Pembayaran →
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function updateDisplay(val) {
    var num = parseFloat(val) || 0;
    document.getElementById('weightNum').textContent = num % 1 === 0 ? num : num.toFixed(1);
}

// Simulasi timbang otomatis (random untuk demo)
function simulasiTimbang() {
    var berat = Math.floor(Math.random() * 80) + 20; // random 20-100 kg
    document.getElementById('inputBerat').value = berat;
    updateDisplay(berat);
    
    // Animasi
    var display = document.getElementById('weightDisplay');
    display.style.transform = 'scale(1.05)';
    setTimeout(function() { display.style.transform = 'scale(1)'; }, 200);
}
</script>
</body>
</html>
