<?php
require 'config/database.php';

function resize_and_save_image($source_path, $target_path, $target_width, $target_height) {
    // ... salin fungsi resize_and_save_image dari proses_upload.php ...
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
    $id = (int)$_POST['id'];
    $foto1_lama = $_POST['foto1_lama'];
    $foto2_lama = $_POST['foto2_lama'];
    $files = $_FILES['foto'];

    $nama_foto_baru = [];
    
    // Cek apakah ada file baru yang diupload
    if (!empty($files['name'][0])) {
        // Validasi jumlah file baru
        if (count($files['name']) !== 2) {
            header("Location: riwayat.php?status=update_gagal&pesan=" . urlencode("Jika ingin mengubah foto, wajib upload 2 foto baru."));
            exit();
        }

        // Proses upload file baru
        for ($i = 0; $i < count($files['name']); $i++) {
            $tmp_name = $files["tmp_name"][$i];
            $nama_file_unik = uniqid('img_', true) . '.jpg';
            $target_file = "uploads/" . $nama_file_unik;

            if (resize_and_save_image($tmp_name, $target_file, 945, 709)) {
                $nama_foto_baru[] = $nama_file_unik;
            } else {
                header("Location: riwayat.php?status=update_gagal&pesan=" . urlencode("Gagal memproses gambar baru."));
                exit();
            }
        }
        
        // Hapus file foto lama
        if (file_exists('uploads/' . $foto1_lama)) unlink('uploads/' . $foto1_lama);
        if (!empty($foto2_lama) && file_exists('uploads/' . $foto2_lama)) unlink('uploads/' . $foto2_lama);
        
        // Update database dengan nama file baru
        $stmt = $conn->prepare("UPDATE kegiatan SET foto1 = ?, foto2 = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nama_foto_baru[0], $nama_foto_baru[1], $id);

    } else {
        // Jika tidak ada file baru diupload, tidak ada yang perlu diupdate.
        // Langsung redirect kembali.
        header("Location: riwayat.php");
        exit();
    }
    
    if ($stmt->execute()) {
        header("Location: riwayat.php?status=update_sukses");
    } else {
        header("Location: riwayat.php?status=update_gagal&pesan=" . urlencode("Gagal mengupdate database."));
    }
    
    $stmt->close();
    $conn->close();
}
?>