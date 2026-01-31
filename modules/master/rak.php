<?php
$page_title = "Data Rak - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $kode_rak = escape($_POST['kode_rak']);
            $lokasi = escape($_POST['lokasi']);
            $sql = "INSERT INTO rak (kode_rak, lokasi) VALUES ('$kode_rak', '$lokasi')";
            if (query($sql)) $success = "Data rak berhasil ditambahkan!";
            else $error = "Gagal menambahkan data rak!";
        }
        elseif ($action == 'update') {
            $id = $_POST['id_rak'];
            $kode_rak = escape($_POST['kode_rak']);
            $lokasi = escape($_POST['lokasi']);
            $sql = "UPDATE rak SET kode_rak='$kode_rak', lokasi='$lokasi' WHERE id_rak=$id";
            if (query($sql)) $success = "Data rak berhasil diupdate!";
            else $error = "Gagal mengupdate data rak!";
        }
        elseif ($action == 'delete') {
            $id = $_POST['id_rak'];
            $sql = "DELETE FROM rak WHERE id_rak=$id";
            if (query($sql)) $success = "Data rak berhasil dihapus!";
            else $error = "Gagal menghapus data rak!";
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM rak WHERE id_rak=$id");
    $edit_data = fetch($result);
}

$data = fetchAll(query("SELECT * FROM rak ORDER BY kode_rak"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-warehouse"></i> Data Rak</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Rak' : 'Tambah Rak Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_rak" value="<?php echo $edit_data['id_rak']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Kode Rak <span style="color: red;">*</span></label>
                <input type="text" name="kode_rak" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['kode_rak'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Lokasi</label>
                <input type="text" name="lokasi" class="form-control" 
                       value="<?php echo $edit_data ? $edit_data['lokasi'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="rak.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Rak</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Kode Rak</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_rak']; ?></td>
                            <td><span class="badge badge-success"><?php echo $row['kode_rak']; ?></span></td>
                            <td><?php echo $row['lokasi']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_rak']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_rak" value="<?php echo $row['id_rak']; ?>">
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
