<?php
date_default_timezone_set('Asia/Makassar');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kegiatan - Rumah Pilah Sampah Telawang</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="assets/logo.png">
    <link rel="manifest" href="manifest.json">

    <meta name="theme-color" content="#2c5282">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        :root{--primary-color:#2c5282;--secondary-color:#276749;--warning-color:#d97706;--danger-color:#dc2626;--background-color:#f7fafc;--card-bg-color:#fff;--text-color:#2d3748;--text-color-light:#718096;--border-color:#e2e8f0;--shadow-color:rgba(45,55,72,.08)}
        body{font-family:'Poppins',sans-serif;background-color:var(--background-color);margin:0;padding:2rem;color:var(--text-color)}
        .container{max-width:1200px;margin:0 auto}
        .page-header{text-align:center;margin-bottom:2rem}
        .page-header h1{font-size:28px;font-weight:700;color:var(--primary-color);margin:0}
        .page-header p{font-size:16px;color:var(--text-color-light);margin-top:.5rem}
        .page-actions{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap: wrap; gap: 1rem;}
        .form-input,.btn{padding:.75rem 1.25rem;font-size:.9rem;border-radius:8px;border:1px solid var(--border-color);font-family:'Poppins',sans-serif;transition:all .2s ease-in-out;background-color:#fff}
        .form-input:focus{outline:none;border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(44,82,130,.1)}
        .btn{cursor:pointer;font-weight:600;border:none;color:#fff;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem}
        .btn:hover{transform:translateY(-2px);box-shadow:0 4px 10px rgba(0,0,0,.1)}
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }
        .btn-secondary{background-color:#718096}.btn-secondary:hover{background-color:#4a5568}
        .btn-primary{background-color:var(--primary-color)}.btn-primary:hover{background-color:#2a4365}
        .btn-action{background-color:var(--secondary-color)}.btn-action:hover{background-color:#22543d}
        .filter-card{background-color:var(--card-bg-color);border-radius:12px;box-shadow:0 4px 15px var(--shadow-color);margin-bottom:1.5rem}
        .filter-tabs{display:flex;border-bottom:1px solid var(--border-color)}
        .filter-tab{padding:1rem 1.5rem;cursor:pointer;font-weight:500;font-family:'Poppins',sans-serif;color:var(--text-color-light);border:none;background:none;border-bottom:3px solid transparent;transition:all .2s ease}
        .filter-tab.active{color:var(--primary-color);border-bottom:3px solid var(--primary-color)}
        .filter-content-wrapper{padding:1.5rem}
        .filter-content{display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap}
        .filter-group{display:flex;align-items:center;gap:.75rem;flex-wrap:wrap}
        .filter-group label{font-weight:500;color:var(--text-color-light)}
        .hidden{display:none!important}
        .sort-container{display:flex;justify-content:flex-end;margin-bottom:24px}
        .report-grid{display:grid;gap:2rem;grid-template-columns:1fr}
        @media (min-width:992px){.report-grid{grid-template-columns:repeat(2,1fr)}}
        .report-card{background-color:var(--card-bg-color);border-radius:12px;box-shadow:0 4px 15px var(--shadow-color);overflow:hidden;display:flex;flex-direction:column}
        .card-header{padding:1.25rem 1.5rem;background-color:#fdfdff;border-bottom:1px solid var(--border-color)}
        .card-header h2{margin:0;font-size:1.2rem;font-weight:600;color:var(--primary-color)}
        .card-footer{display:flex;justify-content:flex-end;align-items:center;gap:.75rem;padding:1rem 1.5rem;background-color:#fcfdff;border-top:1px solid var(--border-color)}
        .btn-edit{background-color:var(--warning-color)}.btn-delete{background-color:var(--danger-color)}
        .no-data{text-align:center;padding:4rem;font-size:1.2rem;color:var(--text-color-light);background-color:var(--card-bg-color);border-radius:12px;box-shadow:0 4px 15px var(--shadow-color)}
        .card-body{padding:1.5rem;display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;flex-grow:1}
        .photo-container{position:relative;width:100%;padding-top:75%;border-radius:8px;overflow:hidden;background-color:#f0f2f5}
        .photo-container img{position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;transition:transform .3s ease}
        .photo-container:hover img{transform:scale(1.1)}
        .pagination-container{display:flex;justify-content:center;align-items:center;padding:2rem 0}
        .pagination a{color:var(--primary-color);padding:.75rem 1rem;text-decoration:none;transition:background-color .3s;border:1px solid var(--border-color);margin:0 4px;border-radius:8px;font-weight:500}
        .pagination a.active{background-color:var(--primary-color);color:#fff;border-color:var(--primary-color)}
        .pagination a:hover:not(.active){background-color:var(--border-color)}
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Riwayat Laporan Kegiatan</h1>
            <p>Rumah Pilah Sampah Kelurahan Telawang</p>
        </div>
        
        <form id="mainFilterForm" method="GET" action="">
            <div class="page-actions">
                <a href="index.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.5 7.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/></svg>
                    <span>Kembali</span>
                </a>
                <a href="#" id="exportBtn" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zm-1 4v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 11.293V7.5a.5.5 0 0 1 1 0z"/></svg>
                    <span>Ekspor Laporan</span>
                </a>
            </div>
            
            <div class="filter-card">
                <div class="filter-tabs">
                    <button type="button" class="filter-tab active" data-target="#filterCepat">Filter Cepat</button>
                    <button type="button" class="filter-tab" data-target="#filterRentang">Rentang Kustom</button>
                </div>
                <div class="filter-content-wrapper">
                    <div id="filterCepat" class="filter-content">
                         <div class="filter-group">
                            <label for="filter">Tampilkan Data:</label>
                            <select name="filter" class="form-input">
                                <option value="semua" <?= (isset($_GET['filter']) && $_GET['filter'] == 'semua') ? 'selected' : ''; ?>>Semua</option>
                                <option value="minggu" <?= (isset($_GET['filter']) && $_GET['filter'] == 'minggu') ? 'selected' : ''; ?>>Minggu Ini</option>
                                <option value="bulan" <?= (isset($_GET['filter']) && $_GET['filter'] == 'bulan') ? 'selected' : ''; ?>>Bulan Ini</option>
                                <option value="tahun" <?= (isset($_GET['filter']) && $_GET['filter'] == 'tahun') ? 'selected' : ''; ?>>Tahun Ini</option>
                            </select>
                        </div>
                        <button type="submit" name="terapkan_filter_cepat" value="1" class="btn btn-action">Terapkan</button>
                    </div>
                    <div id="filterRentang" class="filter-content hidden">
                        <div class="filter-group">
                            <label for="start_date">Dari Tanggal:</label>
                            <input type="date" name="start_date" class="form-input" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" max="<?= date('Y-m-d'); ?>">
                            <label for="end_date">Sampai Tanggal:</label>
                            <input type="date" name="end_date" class="form-input" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" max="<?= date('Y-m-d'); ?>">
                        </div>
                        <button type="submit" name="terapkan_tanggal" value="1" class="btn btn-action">Terapkan</button>
                    </div>
                </div>
            </div>
            <div class="sort-container">
                <div class="filter-group">
                    <label for="sort">Urutkan:</label>
                    <select name="sort" id="sort" class="form-input" onchange="this.form.submit()">
                        <option value="desc" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'desc') ? 'selected' : ''; ?>>Terbaru</option>
                        <option value="asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'asc') ? 'selected' : ''; ?>>Terlama</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="report-grid">
            <?php
            require 'config/database.php';
            $items_per_page=10;
            $current_page=isset($_GET['page'])?(int)$_GET['page']:1;
            $offset=($current_page-1)*$items_per_page;
            
            // --- LOGIKA FILTER FINAL (DIPERBAIKI) ---
            $filter_query = "";

            // Cek tombol mana yang diklik untuk menentukan filter aktif
            if (isset($_GET['terapkan_tanggal'])) {
                if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
                    $start_date = $conn->real_escape_string($_GET['start_date']);
                    $end_date = $conn->real_escape_string($_GET['end_date']);
                    $filter_query = " WHERE tanggal BETWEEN '{$start_date}' AND '{$end_date}'";
                }
            } 
            // Jika tombol filter tanggal tidak diklik, gunakan filter cepat
            else {
                $filter_value = $_GET['filter'] ?? 'semua';
                if ($filter_value == 'minggu') { $filter_query = " WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)"; }
                elseif ($filter_value == 'bulan') { $filter_query = " WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())"; }
                elseif ($filter_value == 'tahun') { $filter_query = " WHERE YEAR(tanggal) = YEAR(CURDATE())"; }
            }

            $sort_order = isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'ASC' : 'DESC';
            
            $total_records_sql = "SELECT COUNT(id) AS total FROM kegiatan" . $filter_query;
            $total_result = $conn->query($total_records_sql);
            $total_records = $total_result->fetch_assoc()['total'];
            $total_pages = ceil($total_records / $items_per_page);

            $sql = "SELECT id, tanggal, foto1, foto2, foto3 FROM kegiatan" . $filter_query . " ORDER BY tanggal {$sort_order}, id {$sort_order} LIMIT {$items_per_page} OFFSET {$offset}";
            $result = $conn->query($sql);
            
            // ... sisa kode tidak berubah ...
            if($result&&$result->num_rows>0){function format_tanggal_indonesia($date){$hari=["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];$bulan=["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];$timestamp=strtotime($date);return $hari[date('w',$timestamp)].", ".date('d',$timestamp)." ".$bulan[date('n',$timestamp)]." ".date('Y',$timestamp);}
            while($row=$result->fetch_assoc()){
                echo '<div class="report-card">';
                echo '  <div class="card-header"><h2>'.format_tanggal_indonesia($row['tanggal']).'</h2></div>';
                echo '  <div class="card-body">';
                if (!empty($row['foto1'])) { echo '<a href="uploads/'.htmlspecialchars($row['foto1']).'" target="_blank"><div class="photo-container"><img src="uploads/'.htmlspecialchars($row['foto1']).'"></div></a>'; }
                if (!empty($row['foto2'])) { echo '<a href="uploads/'.htmlspecialchars($row['foto2']).'" target="_blank"><div class="photo-container"><img src="uploads/'.htmlspecialchars($row['foto2']).'"></div></a>'; }
                if (!empty($row['foto3'])) { echo '<a href="uploads/'.htmlspecialchars($row['foto3']).'" target="_blank"><div class="photo-container"><img src="uploads/'.htmlspecialchars($row['foto3']).'"></div></a>'; }
                echo '  </div>';
                echo '  <div class="card-footer">';
                echo '      <a href="edit.php?id='.$row['id'].'" class="btn btn-sm btn-edit">Edit</a>';
                echo '      <a href="#" onclick="confirmDelete('.$row['id'].')" class="btn btn-sm btn-delete">Hapus</a>';
                echo '  </div>';
                echo '</div>';
            }}else{echo "<div class='no-data'>Belum ada data kegiatan yang tersimpan untuk filter ini.</div>";}
            ?>
        </div>
        
        <div class="pagination-container">
            <div class="pagination">
                <?php if($total_pages>1){$query_params=$_GET;for($i=1;$i<=$total_pages;$i++){$query_params['page']=$i;$link='riwayat.php?'.http_build_query($query_params);$active_class=($i==$current_page)?'active':'';echo "<a href='{$link}' class='{$active_class}'>{$i}</a>";}} ?>
            </div>
        </div>
        <?php $conn->close(); ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Skrip untuk Tab
            const tabs=document.querySelectorAll('.filter-tab');
            const contents=document.querySelectorAll('.filter-content');
            tabs.forEach(tab=>{tab.addEventListener('click',function(event){event.preventDefault();tabs.forEach(item=>item.classList.remove('active'));contents.forEach(content=>content.classList.add('hidden'));this.classList.add('active');const target=document.querySelector(this.dataset.target);if(target){target.classList.remove('hidden')}})});
            
            // Logika untuk mempertahankan tab aktif saat reload
            const urlParams=new URLSearchParams(window.location.search);
            if(urlParams.get('terapkan_tanggal')){
                document.querySelector('.filter-tab[data-target="#filterRentang"]').click();
            } else {
                 document.querySelector('.filter-tab[data-target="#filterCepat"]').click();
            }
            
            // Logika Ekspor
            const exportBtn=document.getElementById('exportBtn');
            exportBtn.addEventListener('click',function(e){e.preventDefault();const currentParams=window.location.search;window.location.href='export_word.php'+currentParams});
            
            // Logika Notifikasi
            const params=new URLSearchParams(window.location.search);
            if(params.has('status')){
                const status = params.get('status');
                const showNotification=(title,text,icon)=>{Swal.fire({title:title,text:text,icon:icon,confirmButtonColor:'var(--primary-color)'});params.delete('status');params.delete('pesan');window.history.replaceState({},document.title,window.location.pathname+'?'+params.toString());};
                if(status==='hapus_sukses'){showNotification('Berhasil!','Data laporan telah dihapus.','success')}
                else if(status==='hapus_gagal'){showNotification('Gagal!','Data laporan gagal dihapus.','error')}
                else if(status==='update_sukses'){showNotification('Berhasil!','Data laporan telah diperbarui.','success')}
                else if(status==='update_gagal'){showNotification('Gagal!',params.get('pesan')||'Data laporan gagal diperbarui.','error')}
            }
        });
        
        // Fungsi Hapus
        function confirmDelete(id){
            Swal.fire({title:'Apakah Anda yakin?',text:"Data yang sudah dihapus tidak bisa dikembalikan!",icon:'warning',showCancelButton:true,confirmButtonColor:'var(--danger-color)',cancelButtonColor:'var(--secondary-color)',confirmButtonText:'Ya, hapus!',cancelButtonText:'Batal'})
            .then((result)=>{if(result.isConfirmed){window.location.href='hapus.php?id='+id}})
        }
    </script>
</body>
</html>