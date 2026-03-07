<?php
session_start();
require_once 'database.php';

// Simpan log logout jika ada session staff
if (!empty($_SESSION['staff'])) {
    $staff = $_SESSION['staff'];
    simpanLog($staff['username'], $staff['role'], 'Logout', '');
}

session_destroy();

header('Location: login.php');
exit;
?>
