<?php
// Koneksi database menggunakan SQLite
function getDB() {
    $db = new PDO('sqlite:' . __DIR__ . '/penggilingan.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

function setupDatabase() {
    $db = getDB();
    
    // Tabel pesanan
    $db->exec("CREATE TABLE IF NOT EXISTS pesanan (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id TEXT UNIQUE,
        nomor_antrian INTEGER,
        nama_pelanggan TEXT,
        nomor_telepon TEXT,
        berat_padi REAL,
        jenis_padi TEXT DEFAULT '',
        jenis_penggilingan TEXT DEFAULT '',
        harga_per_kg REAL DEFAULT 500,
        total_bayar REAL,
        metode_bayar TEXT DEFAULT 'cash',
        status TEXT DEFAULT 'antrian',
        hasil_beras REAL DEFAULT 0,
        hasil_dedak REAL DEFAULT 0,
        estimasi_selesai TEXT DEFAULT '',
        waktu_pesan DATETIME DEFAULT CURRENT_TIMESTAMP,
        waktu_proses DATETIME,
        waktu_selesai DATETIME
    )");
    
    // Tabel tarif
    $db->exec("CREATE TABLE IF NOT EXISTS tarif (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        harga_per_kg REAL DEFAULT 500,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Tabel log aktivitas
    $db->exec("CREATE TABLE IF NOT EXISTS log_aktivitas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT,
        role TEXT,
        aksi TEXT,
        detail TEXT DEFAULT '',
        waktu DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Tabel users
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT,
        role TEXT,
        nama TEXT
    )");
    
    $cek = $db->query("SELECT COUNT(*) FROM tarif")->fetchColumn();
    if ($cek == 0) {
        $db->exec("INSERT INTO tarif (harga_per_kg) VALUES (500)");
    }
    
    $cekUser = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($cekUser == 0) {
        $db->exec("INSERT INTO users (username, password, role, nama) VALUES ('operator', 'pass123', 'operator', 'Budi')");
        $db->exec("INSERT INTO users (username, password, role, nama) VALUES ('owner', 'pass123', 'owner', 'Yanto')");
    }
}

setupDatabase();

// Fungsi ambil tarif saat ini
function getTarif() {
    $db = getDB();
    $tarif = $db->query("SELECT harga_per_kg FROM tarif ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    return $tarif ? $tarif['harga_per_kg'] : 500;
}

// Fungsi ambil nomor antrian berikutnya
function getNomorAntrian() {
    $db = getDB();
    $max = $db->query("SELECT MAX(nomor_antrian) FROM pesanan")->fetchColumn();
    return $max ? $max + 1 : 1;
}

// Fungsi buat order ID unik
function buatOrderId() {
    return 'ORD' . time() . rand(100, 999);
}

// Fungsi simpan log
function simpanLog($username, $role, $aksi, $detail = '') {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO log_aktivitas (username, role, aksi, detail) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $role, $aksi, $detail]);
}
?>
