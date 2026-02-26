<?php
session_start();
require_once 'database.php';

// Cek session
if (empty($_SESSION['pesanan_baru']['berat'])) {
    header('Location: penimbangan.php');
    exit;
}

$pesanan = $_SESSION['pesanan_baru'];
$tarif = getTarif();
$total = $pesanan['berat'] * $tarif;
$error = '';

// Proses pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode'] ?? 'cash';
    
    $db = getDB();
    $order_id = buatOrderId();
    $nomor_antrian = getNomorAntrian();
    
    // Hitung estimasi waktu (30 menit per 50 kg)
    $menit_per_kg = 0.6; // 0.6 menit per kg
    $estimasi_menit = max(15, round($pesanan['berat'] * $menit_per_kg));
    $estimasi_selesai = date('H.i', strtotime("+{$estimasi_menit} minutes"));
    
    $stmt = $db->prepare("INSERT INTO pesanan 
        (order_id, nomor_antrian, nama_pelanggan, nomor_telepon, berat_padi, harga_per_kg, total_bayar, metode_bayar, status, estimasi_selesai)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'antrian', ?)");
    
    $stmt->execute([
        $order_id,
        $nomor_antrian,
        $pesanan['nama'],
        $pesanan['telepon'],
        $pesanan['berat'],
        $tarif,
        $total,
        $metode,
        $estimasi_selesai
    ]);
    
    // Simpan data tiket ke session
    $_SESSION['tiket'] = [
        'order_id' => $order_id,
        'nomor_antrian' => $nomor_antrian,
        'nama' => $pesanan['nama'],
        'berat' => $pesanan['berat'],
        'total' => $total,
        'metode' => $metode,
        'estimasi_selesai' => $estimasi_selesai,
        'estimasi_menit' => $estimasi_menit,
    ];
    
    // Hapus session pesanan
    unset($_SESSION['pesanan_baru']);
    
    header('Location: tiket.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <div class="top-bar">
        <a href="penimbangan.php" class="back-btn">← Kembali</a>
        <h1>💳 Pembayaran</h1>
    </div>

    <div class="content">
        <!-- Ringkasan Pesanan -->
        <div class="card">
            <h3 style="font-weight:800; margin-bottom:14px;">Ringkasan Pesanan</h3>
            <div class="detail-row">
                <span class="label">Nama Pelanggan</span>
                <span class="value"><?= htmlspecialchars($pesanan['nama']) ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Berat Padi</span>
                <span class="value"><?= number_format($pesanan['berat'], 1) ?> kg</span>
            </div>
            <div class="detail-row">
                <span class="label">Harga per kg</span>
                <span class="value">Rp <?= number_format($tarif, 0, ',', '.') ?></span>
            </div>
            <div class="divider"></div>
            <div class="detail-row">
                <span style="font-weight:800; font-size:15px;">Total Pembayaran</span>
                <span class="value-orange" style="font-size:20px;">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <form method="POST" id="formBayar">
            <div class="card">
                <h3 style="font-weight:800; margin-bottom:14px;">Metode Pembayaran</h3>

                <div class="payment-option selected" id="opt-cash" onclick="pilihMetode('cash')">
                    <span class="pay-icon">💵</span>
                    <div>
                        <h4>Tunai</h4>
                        <p>Bayar langsung di kasir</p>
                    </div>
                </div>

                <div class="payment-option" id="opt-transfer" onclick="pilihMetode('transfer')">
                    <span class="pay-icon">🏦</span>
                    <div>
                        <h4>Transfer Bank</h4>
                        <p>Transfer ke rekening tujuan</p>
                    </div>
                </div>

                <div class="payment-option" id="opt-qris" onclick="pilihMetode('qris')">
                    <span class="pay-icon">📱</span>
                    <div>
                        <h4>QRIS</h4>
                        <p>Scan kode QR untuk bayar</p>
                    </div>
                </div>

                <input type="hidden" name="metode" id="inputMetode" value="cash">
            </div>

            <button type="submit" class="btn btn-orange">
                ✓ Konfirmasi Pembayaran
            </button>
        </form>
    </div>
</div>

<script>
function pilihMetode(metode) {
    // Reset semua
    document.querySelectorAll('.payment-option').forEach(function(el) {
        el.classList.remove('selected');
    });
    // Aktifkan yang dipilih
    document.getElementById('opt-' + metode).classList.add('selected');
    document.getElementById('inputMetode').value = metode;
}
</script>
</body>
</html>
