<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';

// Fungsi resize gambar tidak berubah
function resize_and_save_image($source_path, $target_path, $target_width, $target_height) {
    list($source_width, $source_height, $source_type) = getimagesize($source_path);
    switch ($source_type) {
        case IMAGETYPE_JPEG: $source_image = imagecreatefromjpeg($source_path); break;
        case IMAGETYPE_PNG: $source_image = imagecreatefrompng($source_path); break;
        case IMAGETYPE_GIF: $source_image = imagecreatefromgif($source_path); break;
        default: return false;
    }
    $target_image = imagecreatetruecolor($target_width, $target_height);
    imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $target_width, $target_height, $source_width, $source_height);
    imagejpeg($target_image, $target_path, 95);
    imagedestroy($source_image);
    imagedestroy($target_image);
    return true;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $files = $_FILES['foto'];
    
    // Validasi dasar (tidak berubah)
    if (empty($tanggal) || empty($files['name'][0])) {
        header("Location: index.php?status=gagal&pesan=" . urlencode("Data tidak lengkap."));
        exit();
    }

    // Validasi jumlah file (tidak berubah)
    $jumlah_file = count($files['name']);
    if ($jumlah_file !== 2) {
        header("Location: index.php?status=gagal&pesan=" . urlencode("Harus mengupload tepat 2 foto."));
        exit();
    }

    // =======================================================
    // PENGECEKAN DATA DUPLIKAT BERDASARKAN TANGGAL
    // =======================================================
    $stmt_cek = $conn->prepare("SELECT id FROM kegiatan WHERE tanggal = ?");
    $stmt_cek->bind_param("s", $tanggal);
    $stmt_cek->execute();
    $result_cek = $stmt_cek->get_result();

    if ($result_cek->num_rows > 0) {
        // Jika data dengan tanggal yang sama ditemukan, hentikan proses
        $stmt_cek->close();
        header("Location: index.php?status=gagal&pesan=" . urlencode("Laporan untuk tanggal ini sudah ada. Silakan pilih tanggal lain."));
        exit();
    }
    // Tutup statement pengecekan
    $stmt_cek->close();
    // =======================================================


    // Jika tidak ada duplikat, lanjutkan proses upload...
    $nama_file_db = [];
    $target_dir = "uploads/";
    
    if (!is_dir($target_dir) || !is_writable($target_dir)) {
        header("Location: index.php?status=gagal&pesan=" . urlencode("Error: Folder uploads tidak ada atau tidak bisa diakses."));
        exit();
    }

    for ($i = 0; $i < $jumlah_file; $i++) {
        $tmp_name = $files["tmp_name"][$i];
        $nama_file_unik = uniqid('img_', true) . '.jpg';
        $target_file = $target_dir . $nama_file_unik;
        
        if (resize_and_save_image($tmp_name, $target_file, 945, 709)) {
            $nama_file_db[] = $nama_file_unik;
        } else {
            header("Location: index.php?status=gagal&pesan=" . urlencode("Gagal memproses gambar."));
            exit();
        }
    }

    $foto1 = $nama_file_db[0];
    $foto2 = $nama_file_db[1];
    $foto3 = NULL;

    $stmt = $conn->prepare("INSERT INTO kegiatan (tanggal, foto1, foto2, foto3) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $tanggal, $foto1, $foto2, $foto3);

    if ($stmt->execute()) {
        header("Location: index.php?status=sukses");
    } else {
        header("Location: index.php?status=gagal&pesan=" . urlencode("Database Error: " . $stmt->error));
    }

    $stmt->close();
    $conn->close();
}
?>