<?php
$page_title = "Buku-Rak - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $id_buku = $_POST['id_buku'];
        $id_rak = $_POST['id_rak'];
        $jumlah = $_POST['jumlah_di_rak'];
        $sql = "INSERT INTO buku_rak (id_buku, id_rak, jumlah_di_rak) VALUES ($id_buku, $id_rak, $jumlah)";
        if (query($sql)) $success = "Penempatan buku di rak berhasil!";
        else $error = "Gagal menempatkan buku!";
    }
    elseif ($_POST['action'] == 'update') {
        $id = $_POST['id_buku_rak'];
        $jumlah = $_POST['jumlah_di_rak'];
        $sql = "UPDATE buku_rak SET jumlah_di_rak=$jumlah WHERE id_buku_rak=$id";
        if (query($sql)) $success = "Data berhasil diupdate!";
        else $error = "Gagal mengupdate data!";
    }
    elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id_buku_rak'];
        $sql = "DELETE FROM buku_rak WHERE id_buku_rak=$id";
        if (query($sql)) $success = "Data berhasil dihapus!";
        else $error = "Gagal menghapus data!";
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM buku_rak WHERE id_buku_rak=$id");
    $edit_data = fetch($result);
}

$sql = "SELECT br.*, b.judul, r.kode_rak, r.lokasi FROM buku_rak br
        JOIN buku b ON br.id_buku = b.id_buku
        JOIN rak r ON br.id_rak = r.id_rak
        ORDER BY r.kode_rak";
$data = fetchAll(query($sql));

$buku_list = fetchAll(query("SELECT * FROM buku ORDER BY judul"));
$rak_list = fetchAll(query("SELECT * FROM rak ORDER BY kode_rak"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-boxes"></i> Penempatan Buku di Rak</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Penempatan' : 'Tambah Penempatan Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_buku_rak" value="<?php echo $edit_data['id_buku_rak']; ?>">
            <?php endif; ?>
            
            <?php if (!$edit_data): ?>
            <div class="form-group">
                <label>Buku <span style="color: red;">*</span></label>
                <select name="id_buku" class="form-control" required>
                    <option value="">-- Pilih Buku --</option>
                    <?php foreach ($buku_list as $b): ?>
                        <option value="<?php echo $b['id_buku']; ?>"><?php echo $b['judul']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Rak <span style="color: red;">*</span></label>
                <select name="id_rak" class="form-control" required>
                    <option value="">-- Pilih Rak --</option>
                    <?php foreach ($rak_list as $r): ?>
                        <option value="<?php echo $r['id_rak']; ?>">
                            <?php echo $r['kode_rak'] . ' - ' . $r['lokasi']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label>Jumlah di Rak <span style="color: red;">*</span></label>
                <input type="number" name="jumlah_di_rak" class="form-control" required min="0"
                       value="<?php echo $edit_data ? $edit_data['jumlah_di_rak'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="buku_rak.php" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Penempatan Buku</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kode Rak</th>
                        <th>Lokasi</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['judul']; ?></td>
                            <td><span class="badge badge-success"><?php echo $row['kode_rak']; ?></span></td>
                            <td><?php echo $row['lokasi']; ?></td>
                            <td><strong><?php echo $row['jumlah_di_rak']; ?></strong></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_buku_rak']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_buku_rak" value="<?php echo $row['id_buku_rak']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
