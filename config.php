<?php
// config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'pers_on_123');
define('DB_PASS', 'pers_on_123');
define('DB_NAME', 'pers_on_123');

// Buat koneksi
 $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Start session
session_start();
?>