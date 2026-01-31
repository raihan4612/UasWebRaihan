<?php
$page_title = "Laporan Peminjaman - Perpustakaan Banjarbaru";
include '../../includes/header.php';

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
            a.id_anggota,
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

// Get anggota untuk filter
$anggota_list = fetchAll(query("SELECT id_anggota, nama FROM anggota ORDER BY nama"));

// Statistik
$total_peminjaman = count($data);
$sudah_kembali = count(array_filter($data, function($d) { return $d['status_kembali'] == 'Sudah Dikembalikan'; }));
$belum_kembali = $total_peminjaman - $sudah_kembali;
$terlambat = count(array_filter($data, function($d) { return $d['status_kembali'] == 'Belum Dikembalikan' && $d['selisih_hari'] > 0; }));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-chart-line"></i> Laporan Peminjaman Buku</h2>
    </div>

    <!-- Statistik -->
    <div class="dashboard-grid" style="margin-bottom: 30px;">
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-content">
                <h3>Total Peminjaman</h3>
                <div class="count"><?php echo $total_peminjaman; ?></div>
                <div class="label">Transaksi</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-content">
                <h3>Sudah Dikembalikan</h3>
                <div class="count"><?php echo $sudah_kembali; ?></div>
                <div class="label">Transaksi</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <h3>Belum Dikembalikan</h3>
                <div class="count"><?php echo $belum_kembali; ?></div>
                <div class="label">Transaksi</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="card-content">
                <h3>Terlambat</h3>
                <div class="count"><?php echo $terlambat; ?></div>
                <div class="label">Transaksi</div>
            </div>
        </div>
    </div>

    <div class="content-box">
        <!-- Filter -->
        <form method="GET" class="flex align-center gap-10 mb-20">
            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <select name="anggota" class="form-control">
                    <option value="">-- Semua Anggota --</option>
                    <?php foreach ($anggota_list as $a): ?>
                        <option value="<?php echo $a['id_anggota']; ?>" <?php echo $filter_anggota == $a['id_anggota'] ? 'selected' : ''; ?>>
                            <?php echo $a['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <input type="month" name="bulan" class="form-control" value="<?php echo $filter_bulan; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
            
            <a href="laporan_peminjaman.php" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
            
            <button type="button" onclick="printReport()" class="btn btn-success">
                <i class="fas fa-print"></i> Cetak
            </button>
            
            <a href="cetak_peminjaman_pdf.php?anggota=<?php echo $filter_anggota; ?>&bulan=<?php echo $filter_bulan; ?>" target="_blank" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </form>

        <h3>Detail Laporan Peminjaman</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pinjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Anggota</th>
                        <th>Kontak</th>
                        <th>Petugas</th>
                        <th>Buku Dipinjam</th>
                        <th>Jml Buku</th>
                        <th>Harus Kembali</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Keterlambatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_pinjam']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                            <td>
                                <strong><?php echo $row['nama_anggota']; ?></strong><br>
                                <small><?php echo $row['alamat']; ?></small>
                            </td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td><?php echo $row['nama_petugas']; ?></td>
                            <td>
                                <small><?php echo $row['judul_buku']; ?></small>
                            </td>
                            <td><span class="badge badge-success"><?php echo $row['jumlah_buku']; ?></span></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_harus_kembali'])); ?></td>
                            <td>
                                <?php echo $row['tanggal_kembali'] ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-'; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $row['status_kembali'] == 'Sudah Dikembalikan' ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo $row['status_kembali']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status_kembali'] == 'Belum Dikembalikan' && $row['selisih_hari'] > 0): ?>
                                    <span class="badge badge-danger"><?php echo $row['selisih_hari']; ?> hari</span>
                                <?php elseif ($row['status_kembali'] == 'Sudah Dikembalikan' && $row['selisih_hari'] > 0): ?>
                                    <span class="badge badge-warning"><?php echo $row['selisih_hari']; ?> hari</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Tepat Waktu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .page-header button, .form-group, form, .action-buttons, .btn { display: none !important; }
    .content-box { box-shadow: none; border: 1px solid #000; padding: 20px; }
    body { background: white; }
    .container { max-width: 100%; }
    table { font-size: 10px; page-break-inside: auto; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    .dashboard-grid { display: flex !important; margin-bottom: 20px; }
    .dashboard-card { box-shadow: none; border: 1px solid #000; }
    h2, h3 { color: #000; }
}
</style>

<script>
function printReport() {
    window.print();
}
</script>

<?php include '../../includes/footer.php'; ?>
