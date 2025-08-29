<?php
require 'config/database.php';

// Cek apakah ID dikirim melalui URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Ambil nama file gambar dari database sebelum dihapus
    $stmt_select = $conn->prepare("SELECT foto1, foto2, foto3 FROM kegiatan WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();
    $stmt_select->close();

    // 2. Hapus file gambar dari folder 'uploads/'
    if ($row) {
        if (!empty($row['foto1']) && file_exists('uploads/' . $row['foto1'])) {
            unlink('uploads/' . $row['foto1']);
        }
        if (!empty($row['foto2']) && file_exists('uploads/' . $row['foto2'])) {
            unlink('uploads/' . $row['foto2']);
        }
        if (!empty($row['foto3']) && file_exists('uploads/' . $row['foto3'])) {
            unlink('uploads/' . $row['foto3']);
        }
    }

    // 3. Hapus data dari tabel 'kegiatan'
    $stmt_delete = $conn->prepare("DELETE FROM kegiatan WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Jika berhasil, kembali ke halaman riwayat dengan notifikasi sukses
        header("Location: riwayat.php?status=hapus_sukses");
    } else {
        // Jika gagal, kembali dengan notifikasi error
        header("Location: riwayat.php?status=hapus_gagal");
    }
    $stmt_delete->close();
}
$conn->close();
exit();
?>