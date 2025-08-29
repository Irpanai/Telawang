<?php
date_default_timezone_set('Asia/Makassar');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Kegiatan - Rumah Pilah Sampah Telawang</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="manifest" href="manifest.json">

    <meta name="theme-color" content="#2c5282">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root{--primary-color:#2c5282;--secondary-color:#276749;--background-color:#f7fafc;--card-bg-color:#fff;--text-color:#2d3748;--text-color-light:#718096;--border-color:#e2e8f0;--shadow-color:rgba(45,55,72,.1)}
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
        .file-upload-info{font-size:.8rem;color:var(--text-color-light);text-align:center;margin-top:.5rem}
        .btn-container{margin-top:2rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .btn{padding:.85rem 1.25rem;font-size:1rem;border-radius:8px;font-family:'Poppins',sans-serif;transition:all .2s ease-in-out;cursor:pointer;font-weight:600;border:none;color:#fff;text-decoration:none;text-align:center}
        .btn:hover{transform:translateY(-2px);box-shadow:0 4px 10px rgba(0,0,0,.15)}
        .btn-action{background-color:var(--secondary-color)}.btn-action:hover{background-color:#22543d}
        .btn-primary{background-color:var(--primary-color)}.btn-primary:hover{background-color:#2a4365}
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
                <h1>Form Laporan Kegiatan</h1>
                <p>Rumah Pilah Sampah Kelurahan Telawang</p>
            </div>

            <form action="proses_upload.php" method="post" enctype="multipart/form-data" id="laporanForm">
                <div class="form-group">
                    <label for="tanggal">Tanggal Kegiatan</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-input" required max="<?= date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="foto">Upload Foto Kegiatan</label>
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
                    <p class="file-upload-info">Harus upload tepat 2 foto.</p>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-action">Simpan Laporan</button>
                    <a href="riwayat.php" class="btn btn-primary">Lihat Riwayat</a>
                </div>
            </form>
        </div>
    </div>

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
    
    <?php
    if (isset($_GET['status'])) {
        echo "<script>";
        if ($_GET['status'] == 'sukses') {
            echo "Swal.fire({
                title: 'Berhasil!',
                text: 'Laporan berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: 'var(--secondary-color)',
                confirmButtonText: 'Lanjutkan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'riwayat.php';
                }
            });";
        } elseif ($_GET['status'] == 'gagal' && isset($_GET['pesan'])) {
            // Menggunakan json_encode untuk menangani karakter khusus di pesan error
            $pesan_error = json_encode($_GET['pesan']);
            echo "Swal.fire({
                title: 'Gagal!',
                text: " . $pesan_error . ",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi'
            });";
        }
        echo "</script>";
    }
    ?>

</body>
</html>