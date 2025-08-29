<?php
// Konfigurasi Database
$db_host = 'localhost';     // Biasanya 'localhost'
$db_user = 'root';          // User database Anda
$db_pass = '';              // Password database Anda
$db_name = 'db_pilah_sampah'; // Nama database yang sudah dibuat

// Membuat Koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, 3307);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>