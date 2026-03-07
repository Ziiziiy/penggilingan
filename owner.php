<?php
session_start();
require_once 'database.php';

// Cek login owner
if (empty($_SESSION['staff']) || $_SESSION['staff']['role'] !== 'owner') {
    header('Location: login.php');
    exit;
}

$staff = $_SESSION['staff'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="top-bar">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h1>🌾 Dashboard Owner</h1>
                <div class="subtitle"><?= htmlspecialchars($staff['nama']) ?></div>
            </div>
            <a href="logout.php" class="logout-btn">→ Logout</a>
        </div>
    </div>
</body>
</html>
