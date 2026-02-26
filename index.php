<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penggilingan Padi BangunRejo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <!-- Header -->
    <div class="top-bar kiosk-header">
        <h1>🌾 Penggilingan Padi</h1>
        <p>Sistem Antrian Digital BangunRejo</p>
    </div>

    <div class="content">
        <!-- Nomor Antrian Aktif -->
        <div class="card card-orange" style="border-radius:18px; margin-bottom:20px;">
            <p class="antrian-label">Nomor Antrian Saat Ini</p>
            <div class="antrian-number">0</div>
            <p style="font-size:13px; opacity:0.85; margin-top:8px;">
                 antrian aktif
            </p>
        </div>

        <!-- Menu Utama -->
        <a href="buat_pesanan.php" class="menu-item">
            <div class="menu-icon icon-orange">📋</div>
            <div>
                <h3>Buat Pesanan Baru</h3>
                <p>Mulai proses penggilingan padi Anda</p>
            </div>
        </a>

        <a href="status_antrian.php" class="menu-item">
            <div class="menu-icon icon-blue">🕐</div>
            <div>
                <h3>Cek Status Antrian</h3>
                <p>Lihat status dan estimasi waktu selesai</p>
            </div>
        </a>

        <a href="ambil_hasil.php" class="menu-item">
            <div class="menu-icon icon-green">📦</div>
            <div>
                <h3>Ambil Hasil</h3>
                <p>Konfirmasi pengambilan hasil penggilingan</p>
            </div>
        </a>

        <!-- Login Staff -->
        <a href="login.php" class="btn btn-white" style="margin-top:8px;">
            👤 Login Staff
        </a>
    </div>
</div>
</body>
</html>
