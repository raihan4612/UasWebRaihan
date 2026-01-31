<?php
require_once '../../config/database.php';

// Get filter parameters
$filter_anggota = isset($_GET['anggota']) ? $_GET['anggota'] : '';
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

// Build SQL with filters
$where = "WHERE 1=1";
if ($filter_anggota) {
    $where .= " AND a.id_anggota = $filter_anggota";
}
if ($filter_bulan) {
    $where .= " AND DATE_FORMAT(pg.tanggal_kembali, '%Y-%m') = '$filter_bulan'";
}

// Query dengan JOIN beberapa tabel
$sql = "SELECT 
            d.id_denda,
            d.catatan,
            dk.kondisi,
            b.judul,
            td.jenis_kerusakan,
            td.jumlah_denda,
            pg.tanggal_kembali,
            a.nama as nama_anggota,
            a.alamat,
            pt.nama as nama_petugas,
            pengarang.nama as nama_pengarang,
            penerbit.nama as nama_penerbit,
            DATEDIFF(pg.tanggal_kembali, pm.tanggal_harus_kembali) as keterlambatan
        FROM denda d
        JOIN detail_pengembalian dk ON d.id_detail_kembali = dk.id_detail_kembali
        JOIN buku b ON dk.id_buku = b.id_buku
        JOIN pengarang ON b.id_pengarang = pengarang.id_pengarang
        JOIN penerbit ON b.id_penerbit = penerbit.id_penerbit
        JOIN tarif_denda td ON d.id_tarif = td.id_tarif
        JOIN pengembalian pg ON dk.id_kembali = pg.id_kembali
        JOIN peminjaman pm ON pg.id_pinjam = pm.id_pinjam
        JOIN anggota a ON pm.id_anggota = a.id_anggota
        JOIN petugas pt ON pm.id_petugas = pt.id_petugas
        $where
        ORDER BY pg.tanggal_kembali DESC, d.id_denda DESC";

$data = fetchAll(query($sql));

// Statistik
$total_denda = count($data);
$total_nominal = array_sum(array_column($data, 'jumlah_denda'));
$rusak_ringan = count(array_filter($data, function($d) { return $d['kondisi'] == 'Rusak Ringan'; }));
$rusak_berat = count(array_filter($data, function($d) { return $d['kondisi'] == 'Rusak Berat'; }));

// Include TCPDF library
require_once('../../vendor/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Perpustakaan Banjarbaru');
$pdf->SetAuthor('Perpustakaan Banjarbaru');
$pdf->SetTitle('Laporan Denda');
$pdf->SetSubject('Laporan Denda');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

// Set font
$pdf->SetFont('helvetica', '', 9);

// Add a page
$pdf->AddPage();

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'LAPORAN DENDA PERPUSTAKAAN', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Perpustakaan Banjarbaru', 0, 1, 'C');
$pdf->Ln(3);

// Filter info
if ($filter_anggota || $filter_bulan) {
    $pdf->SetFont('helvetica', 'I', 9);
    $filter_text = 'Filter: ';
    if ($filter_bulan) $filter_text .= 'Bulan ' . date('F Y', strtotime($filter_bulan . '-01')) . ' ';
    if ($filter_anggota) {
        $anggota_info = fetch(query("SELECT nama FROM anggota WHERE id_anggota = $filter_anggota"));
        $filter_text .= 'Anggota: ' . $anggota_info['nama'];
    }
    $pdf->Cell(0, 5, $filter_text, 0, 1, 'C');
    $pdf->Ln(2);
}

// Statistik boxes
$pdf->SetFont('helvetica', '', 8);
$box_width = 70;
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell($box_width, 6, 'Total Denda: ' . $total_denda, 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Total Nominal: Rp ' . number_format($total_nominal, 0, ',', '.'), 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Rusak Ringan: ' . $rusak_ringan, 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Rusak Berat: ' . $rusak_berat, 1, 1, 'C', true);
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetFillColor(44, 62, 80);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(8, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Tgl Kembali', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Anggota', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Buku', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Pengarang', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Kondisi', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Jenis Kerusakan', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Nominal', 1, 0, 'C', true);
$pdf->Cell(23, 8, 'Keterlambatan', 1, 1, 'C', true);

// Table data
$pdf->SetFont('helvetica', '', 6);
$pdf->SetTextColor(0, 0, 0);

$no = 1;
foreach ($data as $row) {
    // Alternate row color
    if ($no % 2 == 0) {
        $pdf->SetFillColor(250, 250, 250);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    
    $pdf->Cell(8, 6, $no++, 1, 0, 'C', true);
    $pdf->Cell(15, 6, $row['id_denda'], 1, 0, 'C', true);
    $pdf->Cell(20, 6, date('d/m/Y', strtotime($row['tanggal_kembali'])), 1, 0, 'C', true);
    $pdf->Cell(35, 6, substr($row['nama_anggota'], 0, 22), 1, 0, 'L', true);
    $pdf->Cell(50, 6, substr($row['judul'], 0, 30), 1, 0, 'L', true);
    $pdf->Cell(30, 6, substr($row['nama_pengarang'], 0, 18), 1, 0, 'L', true);
    $pdf->Cell(25, 6, $row['kondisi'], 1, 0, 'C', true);
    $pdf->Cell(35, 6, substr($row['jenis_kerusakan'], 0, 22), 1, 0, 'L', true);
    $pdf->Cell(25, 6, 'Rp ' . number_format($row['jumlah_denda'], 0, ',', '.'), 1, 0, 'R', true);
    
    $keterlambatan = $row['keterlambatan'] > 0 ? $row['keterlambatan'] . ' hari' : 'Tepat';
    $pdf->Cell(23, 6, $keterlambatan, 1, 1, 'C', true);
}

// Total
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(213, 7, 'TOTAL DENDA', 1, 0, 'R', true);
$pdf->Cell(25, 7, 'Rp ' . number_format($total_nominal, 0, ',', '.'), 1, 0, 'R', true);
$pdf->Cell(23, 7, '', 1, 1, 'C', true);

// Footer
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 5, 'Dicetak pada: ' . date('d/m/Y H:i:s'), 0, 1, 'R');

// Output PDF
$filename = 'Laporan_Denda_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');
?>
