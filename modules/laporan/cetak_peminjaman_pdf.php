<?php
require_once '../../config/database.php';

// Get filter parameters
$filter_anggota = isset($_GET['anggota']) ? $_GET['anggota'] : '';
$filter_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

// Build SQL with filters
$where = "WHERE 1=1";
if ($filter_anggota) {
    $where .= " AND pm.id_anggota = $filter_anggota";
}
if ($filter_bulan) {
    $where .= " AND DATE_FORMAT(pm.tanggal_pinjam, '%Y-%m') = '$filter_bulan'";
}

// Query dengan JOIN beberapa tabel
$sql = "SELECT 
            pm.id_pinjam,
            pm.tanggal_pinjam,
            pm.tanggal_harus_kembali,
            a.nama as nama_anggota,
            a.alamat,
            a.no_hp,
            pt.nama as nama_petugas,
            GROUP_CONCAT(b.judul SEPARATOR ', ') as judul_buku,
            COUNT(dp.id_detail) as jumlah_buku,
            CASE 
                WHEN pg.id_kembali IS NULL THEN 'Belum Dikembalikan'
                ELSE 'Sudah Dikembalikan'
            END as status_kembali,
            pg.tanggal_kembali,
            DATEDIFF(COALESCE(pg.tanggal_kembali, CURDATE()), pm.tanggal_harus_kembali) as selisih_hari
        FROM peminjaman pm
        JOIN anggota a ON pm.id_anggota = a.id_anggota
        JOIN petugas pt ON pm.id_petugas = pt.id_petugas
        JOIN detail_peminjaman dp ON pm.id_pinjam = dp.id_pinjam
        JOIN buku b ON dp.id_buku = b.id_buku
        LEFT JOIN pengembalian pg ON pm.id_pinjam = pg.id_pinjam
        $where
        GROUP BY pm.id_pinjam
        ORDER BY pm.tanggal_pinjam DESC";

$data = fetchAll(query($sql));

// Statistik
$total_peminjaman = count($data);
$sudah_kembali = count(array_filter($data, function($d) { return $d['status_kembali'] == 'Sudah Dikembalikan'; }));
$belum_kembali = $total_peminjaman - $sudah_kembali;
$terlambat = count(array_filter($data, function($d) { return $d['status_kembali'] == 'Belum Dikembalikan' && $d['selisih_hari'] > 0; }));

// Include TCPDF library
require_once('../../vendor/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Perpustakaan Banjarbaru');
$pdf->SetAuthor('Perpustakaan Banjarbaru');
$pdf->SetTitle('Laporan Peminjaman Buku');
$pdf->SetSubject('Laporan Peminjaman');

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
$pdf->Cell(0, 10, 'LAPORAN PEMINJAMAN BUKU', 0, 1, 'C');
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

$pdf->Cell($box_width, 6, 'Total Peminjaman: ' . $total_peminjaman, 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Sudah Kembali: ' . $sudah_kembali, 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Belum Kembali: ' . $belum_kembali, 1, 0, 'C', true);
$pdf->Cell($box_width, 6, 'Terlambat: ' . $terlambat, 1, 1, 'C', true);
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetFillColor(44, 62, 80);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(23, 8, 'Tgl Pinjam', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Anggota', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Petugas', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Buku', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Jml', 1, 0, 'C', true);
$pdf->Cell(23, 8, 'Hrs Kembali', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Status', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Keterlambatan', 1, 1, 'C', true);

// Table data
$pdf->SetFont('helvetica', '', 7);
$pdf->SetTextColor(0, 0, 0);

$no = 1;
foreach ($data as $row) {
    // Alternate row color
    if ($no % 2 == 0) {
        $pdf->SetFillColor(250, 250, 250);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    
    $pdf->Cell(10, 6, $no++, 1, 0, 'C', true);
    $pdf->Cell(20, 6, $row['id_pinjam'], 1, 0, 'C', true);
    $pdf->Cell(23, 6, date('d/m/Y', strtotime($row['tanggal_pinjam'])), 1, 0, 'C', true);
    $pdf->Cell(40, 6, substr($row['nama_anggota'], 0, 20), 1, 0, 'L', true);
    $pdf->Cell(25, 6, substr($row['nama_petugas'], 0, 15), 1, 0, 'L', true);
    $pdf->Cell(60, 6, substr($row['judul_buku'], 0, 35), 1, 0, 'L', true);
    $pdf->Cell(15, 6, $row['jumlah_buku'], 1, 0, 'C', true);
    $pdf->Cell(23, 6, isset($row['tanggal_harus_kembali']) ? date('d/m/Y', strtotime($row['tanggal_harus_kembali'])) : '-', 1, 0, 'C', true);
    $pdf->Cell(25, 6, $row['status_kembali'] == 'Sudah Dikembalikan' ? 'Kembali' : 'Belum', 1, 0, 'C', true);
    
    $keterlambatan = '-';
    if ($row['status_kembali'] == 'Belum Dikembalikan' && $row['selisih_hari'] > 0) {
        $keterlambatan = $row['selisih_hari'] . ' hari';
    } elseif ($row['status_kembali'] == 'Sudah Dikembalikan' && $row['selisih_hari'] > 0) {
        $keterlambatan = $row['selisih_hari'] . ' hari';
    } else {
        $keterlambatan = 'Tepat';
    }
    $pdf->Cell(25, 6, $keterlambatan, 1, 1, 'C', true);
}

// Footer
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 5, 'Dicetak pada: ' . date('d/m/Y H:i:s'), 0, 1, 'R');

// Output PDF
$filename = 'Laporan_Peminjaman_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');
?>
