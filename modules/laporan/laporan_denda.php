<?php
$page_title = "Laporan Denda - Perpustakaan Banjarbaru";
include '../../includes/header.php';

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

// Query dengan JOIN beberapa tabel (peminjaman, pengembalian, denda, buku, anggota, tarif)
$sql = "SELECT 
            d.id_denda,
            d.catatan,
            dk.kondisi,
            b.judul,
            b.tahun,
            td.jenis_kerusakan,
            td.jumlah_denda,
            pg.tanggal_kembali,
            pm.tanggal_pinjam,
            pm.tanggal_harus_kembali,
            a.id_anggota,
            a.nama as nama_anggota,
            a.alamat,
            a.no_hp,
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

// Get anggota untuk filter
$anggota_list = fetchAll(query("SELECT id_anggota, nama FROM anggota ORDER BY nama"));

// Statistik
$total_denda = count($data);
$total_nominal = array_sum(array_column($data, 'jumlah_denda'));
$rusak_ringan = count(array_filter($data, function($d) { return $d['kondisi'] == 'Rusak Ringan'; }));
$rusak_berat = count(array_filter($data, function($d) { return $d['kondisi'] == 'Rusak Berat'; }));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-exclamation-triangle"></i> Laporan Denda</h2>
    </div>

    <!-- Statistik -->
    <div class="dashboard-grid" style="margin-bottom: 30px;">
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="card-content">
                <h3>Total Denda</h3>
                <div class="count"><?php echo $total_denda; ?></div>
                <div class="label">Transaksi</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-content">
                <h3>Total Nominal</h3>
                <div class="count" style="font-size: 1.8rem;">Rp <?php echo number_format($total_nominal, 0, ',', '.'); ?></div>
                <div class="label">Rupiah</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="card-content">
                <h3>Rusak Ringan</h3>
                <div class="count"><?php echo $rusak_ringan; ?></div>
                <div class="label">Buku</div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="card-content">
                <h3>Rusak Berat</h3>
                <div class="count"><?php echo $rusak_berat; ?></div>
                <div class="label">Buku</div>
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
            
            <a href="laporan_denda.php" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
            
            <button type="button" onclick="printReport()" class="btn btn-success">
                <i class="fas fa-print"></i> Cetak
            </button>
            
            <a href="cetak_denda_pdf.php?anggota=<?php echo $filter_anggota; ?>&bulan=<?php echo $filter_bulan; ?>" target="_blank" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </form>

        <h3>Detail Laporan Denda</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Denda</th>
                        <th>Tgl Kembali</th>
                        <th>Anggota</th>
                        <th>Kontak</th>
                        <th>Buku</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Kondisi</th>
                        <th>Jenis Kerusakan</th>
                        <th>Nominal Denda</th>
                        <th>Keterlambatan</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_denda']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></td>
                            <td>
                                <strong><?php echo $row['nama_anggota']; ?></strong><br>
                                <small><?php echo $row['alamat']; ?></small>
                            </td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td>
                                <strong><?php echo $row['judul']; ?></strong><br>
                                <small>(<?php echo $row['tahun']; ?>)</small>
                            </td>
                            <td><?php echo $row['nama_pengarang']; ?></td>
                            <td><?php echo $row['nama_penerbit']; ?></td>
                            <td>
                                <span class="badge <?php echo $row['kondisi'] == 'Rusak Ringan' ? 'badge-warning' : 'badge-danger'; ?>">
                                    <?php echo $row['kondisi']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['jenis_kerusakan']; ?></td>
                            <td><strong>Rp <?php echo number_format($row['jumlah_denda'], 0, ',', '.'); ?></strong></td>
                            <td>
                                <?php if ($row['keterlambatan'] > 0): ?>
                                    <span class="badge badge-danger"><?php echo $row['keterlambatan']; ?> hari</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Tepat Waktu</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['nama_petugas']; ?></td>
                            <td><?php echo $row['catatan']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: var(--light-gray); font-weight: bold;">
                        <td colspan="10" style="text-align: right; padding: 15px;">TOTAL DENDA:</td>
                        <td colspan="4" style="padding: 15px;">
                            <strong style="color: var(--danger); font-size: 1.2rem;">
                                Rp <?php echo number_format($total_nominal, 0, ',', '.'); ?>
                            </strong>
                        </td>
                    </tr>
                </tfoot>
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
    table { font-size: 9px; page-break-inside: auto; }
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
