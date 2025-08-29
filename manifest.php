<?php
// Memberitahu browser bahwa ini adalah file JSON
header('Content-Type: application/json');

// Menggunakan heredoc untuk menempelkan JSON Anda dengan mudah
echo <<<JSON
{
  "name": "Laporan Sampah Telawang",
  "short_name": "Lapor Sampah",
  "description": "Aplikasi Pelaporan Kegiatan Rumah Pilah Sampah Kelurahan Telawang.",
  "start_url": "/index.php",
  "display": "standalone",
  "background_color": "#ffffff",
  "theme_color": "#2c5282",
  "icons": [
    {
      "src": "/assets/logo.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any maskable"
    }
  ]
}
JSON;
?>