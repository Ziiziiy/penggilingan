<?php
session_start();
require_once 'database.php';

// Cek login operator
if (empty($_SESSION['staff']) || $_SESSION['staff']['role'] !== 'operator') {
    header('Location: login.php');
    exit;
}

$staff = $_SESSION['staff'];
$db = getDB();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-wrapper">
    <div class="top-bar">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1>🌾 Dashboard Operator</h1>
                <div class="subtitle"><?= htmlspecialchars($staff['nama']) ?></div>
            </div>
            <a href="logout.php" class="logout-btn">→ Logout</a>
        </div>
    </div>
</div>
</body>
</html>
