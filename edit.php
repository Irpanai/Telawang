<?php
require 'config/database.php';
// Atur zona waktu
date_default_timezone_set('Asia/Makassar');

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) {
    header('Location: riwayat.php');
    exit();
}

// Ambil data lama dari database berdasarkan ID
$stmt = $conn->prepare("SELECT tanggal, foto1, foto2 FROM kegiatan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan Kegiatan - Rumah Pilah Sampah</title>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="manifest" href="manifest.json">

    <meta name="theme-color" content="#2c5282">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root{--primary-color:#2c5282;--secondary-color:#d97706;--background-color:#f7fafc;--card-bg-color:#fff;--text-color:#2d3748;--text-color-light:#718096;--border-color:#e2e8f0;--shadow-color:rgba(45,55,72,.1)}
        *{box-sizing:border-box}
        body{font-family:'Poppins',sans-serif;background-color:var(--background-color);margin:0;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:2rem}
        .main-container{display:grid;grid-template-columns:1fr 1.5fr;max-width:1000px;width:100%;background-color:var(--card-bg-color);border-radius:16px;box-shadow:0 10px 30px var(--shadow-color);overflow:hidden}
        .info-panel{position:relative;background-image:url(assets/foto.jpg);background-size:cover;background-position:center;color:#fff;padding:3rem;display:flex;flex-direction:column;justify-content:center;text-align:center;z-index:1}
        .info-panel::before{content:'';position:absolute;top:0;left:0;width:100%;height:100%;background-color:rgba(44,82,130,.7);z-index:-1}
        .info-panel h2{font-size:24px;font-weight:700;margin-top:1.5rem;margin-bottom:1rem}
        .info-panel p{font-size:1rem;opacity:.9;line-height:1.6}
        .info-panel .icon{width:110px;height:140px;margin:0 auto}
        .form-panel{padding:3rem}
        .form-header{text-align:center;margin-bottom:2rem}
        .form-header h1{font-size:1.75rem;color:var(--primary-color);font-weight:700;margin:0}
        .form-header p{color:var(--text-color-light);margin-top:.5rem}
        .form-group{margin-bottom:1.5rem}
        .form-group label{display:block;margin-bottom:.5rem;font-weight:500;color:var(--text-color)}
        .form-input{width:100%;padding:.75rem 1.25rem;font-size:.9rem;border-radius:8px;border:1px solid var(--border-color);font-family:'Poppins',sans-serif;transition:all .2s ease-in-out;background-color:#fdfdff}
        .form-input[readonly]{background-color:#e9ecef;cursor:not-allowed;} /* Style untuk input readonly */
        .form-input:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(44,82,130,.1)}
        .file-upload-wrapper{position:relative}
        .file-upload-wrapper input[type=file]{opacity:0;position:absolute;width:100%;height:100%;cursor:pointer}
        .file-upload-label{display:flex;flex-direction:column;justify-content:center;align-items:center;min-height:100%;padding:1rem;border:2px dashed var(--border-color);border-radius:8px;cursor:pointer;transition:all .2s ease}
        .file-upload-label:hover{background-color:#f8f9fa;border-color:var(--primary-color)}
        .file-upload-label.has-files{border-style:solid;border-color:var(--primary-color);background-color:#f8f9fa}
        #upload-prompt{display:flex;flex-direction:column;align-items:center;text-align:center}
        #upload-prompt svg{width:40px;height:40px;color:var(--primary-color);margin-bottom:.75rem}
        #upload-prompt span{font-weight:500;color:var(--primary-color)}
        #image-preview-container{display:flex;gap:1rem;flex-wrap:wrap;justify-content:center}
        .preview-image{width:100px;height:100px;object-fit:cover;border-radius:8px;border:2px solid var(--border-color)}
        .current-photos{display:flex;gap:1rem;margin-top:0.5rem;}
        .current-photos img{width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border-color)}
        .file-upload-info{font-size:.8rem;color:var(--text-color-light);text-align:center;margin-top:.5rem}
        .btn-container{margin-top:2rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .btn{padding:.85rem 1.25rem;font-size:1rem;border-radius:8px;font-family:'Poppins',sans-serif;transition:all .2s ease-in-out;cursor:pointer;font-weight:600;border:none;color:#fff;text-decoration:none;text-align:center}
        .btn:hover{transform:translateY(-2px);box-shadow:0 4px 10px rgba(0,0,0,.15)}
        .btn-action{background-color:var(--secondary-color)}.btn-action:hover{background-color:#c26e05} /* Warna hover oranye lebih gelap */
        .btn-secondary{background-color:var(--primary-color)}.btn-secondary:hover{background-color:#2a4365}
        @media (max-width:800px){.main-container{grid-template-columns:1fr}.info-panel{display:none}.form-panel,body{padding:1.5rem}}
    </style>
</head>
<body>
    <div class="main-container">
        <div class="info-panel">
            <img src="assets/logo.png" alt="Logo Kota Banjarmasin" class="icon">
            <h2>Lapor & Kelola Sampah</h2>
            <p>Laporkan kegiatan pemilahan sampah harian Anda dengan mudah dan cepat untuk Telawang yang lebih bersih dan sehat.</p>
        </div>

        <div class="form-panel">
            <div class="form-header">
                <h1>Form Edit Laporan</h1>
                <p>Rumah Pilah Sampah Kelurahan Telawang</p>
            </div>
            
            <form action="proses_update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="foto1_lama" value="<?= htmlspecialchars($data['foto1']) ?>">
                <input type="hidden" name="foto2_lama" value="<?= htmlspecialchars($data['foto2']) ?>">

                <div class="form-group">
                    <label for="tanggal">Tanggal Kegiatan (Tidak bisa diubah)</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-input" value="<?= htmlspecialchars($data['tanggal']) ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Foto Saat Ini</label>
                    <div class="current-photos">
                        <img src="uploads/<?= htmlspecialchars($data['foto1']) ?>" alt="Foto 1">
                        <?php if(!empty($data['foto2'])): ?>
                            <img src="uploads/<?= htmlspecialchars($data['foto2']) ?>" alt="Foto 2">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="foto">Upload Foto Baru (Kosongkan jika tidak ingin diubah)</label>
                    <div class="file-upload-wrapper">
                        <input type="file" id="foto" name="foto[]" accept="image/*" multiple required>
                        <label for="foto" class="file-upload-label">
                            <div id="upload-prompt">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                                <span>Pilih 2 File...</span>
                            </div>
                            <div id="image-preview-container"></div>
                        </label>
                    </div>
                    <p class="file-upload-info">Jika diisi, wajib upload 2 foto baru.</p>
                </div>
                
                <div class="btn-container">
                    <button type="submit" class="btn btn-action">Update Laporan</button>
                    <a href="riwayat.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

    <script>
        const fotoInput = document.getElementById('foto');
        const previewContainer = document.getElementById('image-preview-container');
        const uploadPrompt = document.getElementById('upload-prompt');
        const fileUploadLabel = document.querySelector('.file-upload-label');
        fotoInput.addEventListener('change', function() {
            previewContainer.innerHTML = ''; 
            const files = this.files;
            if (files.length > 0) {
                uploadPrompt.style.display = 'none';
                fileUploadLabel.classList.add('has-files');
                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        const img = document.createElement('img');
                        img.src = reader.result;
                        img.classList.add('preview-image');
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                uploadPrompt.style.display = 'flex';
                fileUploadLabel.classList.remove('has-files');
            }
        });
        document.getElementById('laporanForm').addEventListener('submit', function(event) {
            if (fotoInput.files.length !== 2) {
                alert('Harap upload tepat 2 foto.');
                event.preventDefault();
            }
        });
    </script>