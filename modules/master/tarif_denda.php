<?php
$page_title = "Data Tarif Denda - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $jenis_kerusakan = escape($_POST['jenis_kerusakan']);
            $jumlah_denda = $_POST['jumlah_denda'];
            $sql = "INSERT INTO tarif_denda (jenis_kerusakan, jumlah_denda) VALUES ('$jenis_kerusakan', $jumlah_denda)";
            if (query($sql)) $success = "Data tarif denda berhasil ditambahkan!";
            else $error = "Gagal menambahkan data tarif denda!";
        }
        elseif ($action == 'update') {
            $id = $_POST['id_tarif'];
            $jenis_kerusakan = escape($_POST['jenis_kerusakan']);
            $jumlah_denda = $_POST['jumlah_denda'];
            $sql = "UPDATE tarif_denda SET jenis_kerusakan='$jenis_kerusakan', jumlah_denda=$jumlah_denda WHERE id_tarif=$id";
            if (query($sql)) $success = "Data tarif denda berhasil diupdate!";
            else $error = "Gagal mengupdate data tarif denda!";
        }
        elseif ($action == 'delete') {
            $id = $_POST['id_tarif'];
            $sql = "DELETE FROM tarif_denda WHERE id_tarif=$id";
            if (query($sql)) $success = "Data tarif denda berhasil dihapus!";
            else $error = "Gagal menghapus data tarif denda!";
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM tarif_denda WHERE id_tarif=$id");
    $edit_data = fetch($result);
}

$data = fetchAll(query("SELECT * FROM tarif_denda ORDER BY jumlah_denda"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-money-bill"></i> Data Tarif Denda</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Tarif Denda' : 'Tambah Tarif Denda Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_tarif" value="<?php echo $edit_data['id_tarif']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Jenis Kerusakan <span style="color: red;">*</span></label>
                <input type="text" name="jenis_kerusakan" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['jenis_kerusakan'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Jumlah Denda (Rp) <span style="color: red;">*</span></label>
                <input type="number" name="jumlah_denda" class="form-control" required min="0" step="1000"
                       value="<?php echo $edit_data ? $edit_data['jumlah_denda'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="tarif_denda.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Tarif Denda</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Jenis Kerusakan</th>
                        <th>Jumlah Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_tarif']; ?></td>
                            <td><?php echo $row['jenis_kerusakan']; ?></td>
                            <td><strong>Rp <?php echo number_format($row['jumlah_denda'], 0, ',', '.'); ?></strong></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_tarif']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_tarif" value="<?php echo $row['id_tarif']; ?>">
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
