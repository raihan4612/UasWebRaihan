<?php
$page_title = "Data Penerbit - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $nama = escape($_POST['nama']);
            $sql = "INSERT INTO penerbit (nama) VALUES ('$nama')";
            if (query($sql)) $success = "Data penerbit berhasil ditambahkan!";
            else $error = "Gagal menambahkan data penerbit!";
        }
        elseif ($action == 'update') {
            $id = $_POST['id_penerbit'];
            $nama = escape($_POST['nama']);
            $sql = "UPDATE penerbit SET nama='$nama' WHERE id_penerbit=$id";
            if (query($sql)) $success = "Data penerbit berhasil diupdate!";
            else $error = "Gagal mengupdate data penerbit!";
        }
        elseif ($action == 'delete') {
            $id = $_POST['id_penerbit'];
            $sql = "DELETE FROM penerbit WHERE id_penerbit=$id";
            if (query($sql)) $success = "Data penerbit berhasil dihapus!";
            else $error = "Gagal menghapus data penerbit!";
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM penerbit WHERE id_penerbit=$id");
    $edit_data = fetch($result);
}

$data = fetchAll(query("SELECT * FROM penerbit ORDER BY nama"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-building"></i> Data Penerbit</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Penerbit' : 'Tambah Penerbit Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_penerbit" value="<?php echo $edit_data['id_penerbit']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Penerbit <span style="color: red;">*</span></label>
                <input type="text" name="nama" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="penerbit.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Penerbit</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama Penerbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_penerbit']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_penerbit']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_penerbit" value="<?php echo $row['id_penerbit']; ?>">
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
