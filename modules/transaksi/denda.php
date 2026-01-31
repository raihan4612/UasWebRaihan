<?php
$page_title = "Denda - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $id_detail_kembali = $_POST['id_detail_kembali'];
        $id_tarif = $_POST['id_tarif'];
        $catatan = escape($_POST['catatan']);
        $sql = "INSERT INTO denda (id_detail_kembali, id_tarif, catatan) VALUES ($id_detail_kembali, $id_tarif, '$catatan')";
        if (query($sql)) $success = "Denda berhasil ditambahkan!";
        else $error = "Gagal menambahkan denda!";
    }
    elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id_denda'];
        $sql = "DELETE FROM denda WHERE id_denda=$id";
        if (query($sql)) $success = "Denda berhasil dihapus!";
        else $error = "Gagal menghapus denda!";
    }
}

$sql = "SELECT d.*, dk.kondisi, b.judul, td.jenis_kerusakan, td.jumlah_denda,
        pg.id_kembali, pm.id_anggota, a.nama as nama_anggota
        FROM denda d
        JOIN detail_pengembalian dk ON d.id_detail_kembali = dk.id_detail_kembali
        JOIN buku b ON dk.id_buku = b.id_buku
        JOIN tarif_denda td ON d.id_tarif = td.id_tarif
        JOIN pengembalian pg ON dk.id_kembali = pg.id_kembali
        JOIN peminjaman pm ON pg.id_pinjam = pm.id_pinjam
        JOIN anggota a ON pm.id_anggota = a.id_anggota
        ORDER BY d.id_denda DESC";
$data = fetchAll(query($sql));

// Get detail pengembalian yang rusak dan belum kena denda
$detail_list = fetchAll(query("SELECT dk.*, b.judul, pg.id_kembali, pm.id_anggota, a.nama as nama_anggota
                               FROM detail_pengembalian dk
                               JOIN buku b ON dk.id_buku = b.id_buku
                               JOIN pengembalian pg ON dk.id_kembali = pg.id_kembali
                               JOIN peminjaman pm ON pg.id_pinjam = pm.id_pinjam
                               JOIN anggota a ON pm.id_anggota = a.id_anggota
                               WHERE dk.kondisi != 'Baik' 
                               AND dk.id_detail_kembali NOT IN (SELECT id_detail_kembali FROM denda)
                               ORDER BY dk.id_detail_kembali DESC"));

$tarif_list = fetchAll(query("SELECT * FROM tarif_denda ORDER BY jumlah_denda"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-receipt"></i> Data Denda</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3>Tambah Denda Baru</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Buku Rusak <span style="color: red;">*</span></label>
                <select name="id_detail_kembali" class="form-control" required>
                    <option value="">-- Pilih Buku Rusak --</option>
                    <?php foreach ($detail_list as $d): ?>
                        <option value="<?php echo $d['id_detail_kembali']; ?>">
                            <?php echo $d['judul'] . ' - ' . $d['nama_anggota'] . ' (Kondisi: ' . $d['kondisi'] . ')'; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tarif Denda <span style="color: red;">*</span></label>
                <select name="id_tarif" class="form-control" required>
                    <option value="">-- Pilih Tarif --</option>
                    <?php foreach ($tarif_list as $t): ?>
                        <option value="<?php echo $t['id_tarif']; ?>">
                            <?php echo $t['jenis_kerusakan'] . ' - Rp ' . number_format($t['jumlah_denda'], 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Denda</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Kondisi</th>
                        <th>Jenis Kerusakan</th>
                        <th>Denda</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_denda']; ?></td>
                            <td><?php echo $row['nama_anggota']; ?></td>
                            <td><?php echo $row['judul']; ?></td>
                            <td><span class="badge badge-danger"><?php echo $row['kondisi']; ?></span></td>
                            <td><?php echo $row['jenis_kerusakan']; ?></td>
                            <td><strong>Rp <?php echo number_format($row['jumlah_denda'], 0, ',', '.'); ?></strong></td>
                            <td><?php echo $row['catatan']; ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_denda" value="<?php echo $row['id_denda']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
