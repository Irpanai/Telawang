<?php
// 1. Load Composer autoloader dan file konfigurasi database
require 'vendor/autoload.php';
require 'config/database.php';

// 2. Inisialisasi PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Menambahkan properti dokumen
// $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::IN_ID)); // Baris ini dinonaktifkan
$properties = $phpWord->getDocInfo();
$properties->setCreator('Sistem Laporan Telawang');
$properties->setTitle('Laporan Kegiatan Rumah Pilah Sampah');

// 3. Pengaturan Halaman (Ukuran Kertas F4/Folio dan Margin)
$section = $phpWord->addSection([
    'paperSize' => 'Legal',
    'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
    'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
    'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
    'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2),
]);

// 4. Membuat Header yang akan berulang di setiap halaman
$header = $section->addHeader();
$header->addText('Kegiatan Rumah Pilah Sampah Telawang', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
$header->addText('Kelurahan Telawang', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

// 5. Mengambil Data dari Database
// ============================================== -->
$filter_query = "";

// Cek tombol mana yang diklik untuk menentukan filter aktif
if (isset($_GET['terapkan_tanggal']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $start_date = $conn->real_escape_string($_GET['start_date']);
    $end_date = $conn->real_escape_string($_GET['end_date']);
    $filter_query = " WHERE tanggal BETWEEN '{$start_date}' AND '{$end_date}'";
} 
// Jika tombol filter tanggal tidak diklik, gunakan filter cepat
else {
    $filter_value = $_GET['filter'] ?? 'semua';
    if ($filter_value == 'minggu') { $filter_query = " WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)"; }
    elseif ($filter_value == 'bulan') { $filter_query = " WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())"; }
    elseif ($filter_value == 'tahun') { $filter_query = " WHERE YEAR(tanggal) = YEAR(CURDATE())"; }
}

$sort_order = isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'ASC' : 'DESC';
$sql = "SELECT tanggal, foto1, foto2, foto3 FROM kegiatan" . $filter_query . " ORDER BY tanggal {$sort_order}, id {$sort_order}";
$result = $conn->query($sql);

function format_tanggal_indonesia_word($date) {
    $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    $bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    $timestamp = strtotime($date);
    $nama_hari = $hari[date('w', $timestamp)];
    $tgl = date('d', $timestamp);
    $nama_bulan = $bulan[date('n', $timestamp)];
    $tahun = date('Y', $timestamp);
    return "$nama_hari, $tgl $nama_bulan " . $tahun;
}

// 6. Looping dan Membuat Konten Laporan
if ($result && $result->num_rows > 0) {
    $entryCounter = 0;
    $totalEntries = $result->num_rows;

    while($row = $result->fetch_assoc()) {
        $entryCounter++;

        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $phpWord->addTableStyle('reportTableStyle', $tableStyle);
        $table = $section->addTable('reportTableStyle');

        $table->addRow();
        $cellDate = $table->addCell(10000, ['gridSpan' => 2, 'valign' => 'center']);
        $cellDate->addText(format_tanggal_indonesia_word($row['tanggal']), ['bold' => true], ['spaceAfter' => 0]);

        $table->addRow();
        $cellImage1 = $table->addCell(5000);
        $cellImage2 = $table->addCell(5000);

        // =======================================================
        // PERBAIKAN UKURAN GAMBAR DI SINI
        // =======================================================
        if (!empty($row['foto1']) && file_exists('uploads/' . $row['foto1'])) {
            // Gunakan ukuran pixel yang sama persis dengan file yang di-resize
            $cellImage1->addImage('uploads/' . $row['foto1'], ['width' => 245, 'height' => 208, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }
        if (!empty($row['foto2']) && file_exists('uploads/' . $row['foto2'])) {
            // Gunakan ukuran pixel yang sama persis dengan file yang di-resize
            // CORRECT CODE
        $cellImage2->addImage('uploads/' . $row['foto2'], ['width' => 245, 'height' => 208, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }
        
        $section->addTextBreak(1);

        if ($entryCounter % 3 === 0 && $entryCounter < $totalEntries) {
            $section->addPageBreak();
        }
    }
} else {
    $section->addText("Tidak ada data untuk ditampilkan.", null, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
}

$conn->close();

// 8. Simpan dan Kirim File ke Browser
$filename = "Laporan-Kegiatan-Pilah-Sampah.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit;