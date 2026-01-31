<?php
$page_title = "Data Anggota - Perpustakaan Banjarbaru";
include '../../includes/header.php';

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $nama = escape($_POST['nama']);
            $alamat = escape($_POST['alamat']);
            $no_hp = escape($_POST['no_hp']);
            $status = escape($_POST['status']);
            
            $sql = "INSERT INTO anggota (nama, alamat, no_hp, status) VALUES ('$nama', '$alamat', '$no_hp', '$status')";
            if (query($sql)) {
                $success = "Data anggota berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan data anggota!";
            }
        }
        
        elseif ($action == 'update') {
            $id = $_POST['id_anggota'];
            $nama = escape($_POST['nama']);
            $alamat = escape($_POST['alamat']);
            $no_hp = escape($_POST['no_hp']);
            $status = escape($_POST['status']);
            
            $sql = "UPDATE anggota SET nama='$nama', alamat='$alamat', no_hp='$no_hp', status='$status' WHERE id_anggota=$id";
            if (query($sql)) {
                $success = "Data anggota berhasil diupdate!";
            } else {
                $error = "Gagal mengupdate data anggota!";
            }
        }
        
        elseif ($action == 'delete') {
            $id = $_POST['id_anggota'];
            $sql = "DELETE FROM anggota WHERE id_anggota=$id";
            if (query($sql)) {
                $success = "Data anggota berhasil dihapus!";
            } else {
                $error = "Gagal menghapus data anggota!";
            }
        }
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM anggota WHERE id_anggota=$id");
    $edit_data = fetch($result);
}

// Get all data
$result = query("SELECT * FROM anggota ORDER BY id_anggota DESC");
$data_anggota = fetchAll($result);
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-users"></i> Data Anggota</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Anggota' : 'Tambah Anggota Baru'; ?></h3>
        <form method="POST" id="anggotaForm">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_anggota" value="<?php echo $edit_data['id_anggota']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Lengkap <span style="color: red;">*</span></label>
                <input type="text" name="nama" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3"><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" 
                       value="<?php echo $edit_data ? $edit_data['no_hp'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Status <span style="color: red;">*</span></label>
                <select name="status" class="form-control" required>
                    <option value="Aktif" <?php echo ($edit_data && $edit_data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo ($edit_data && $edit_data['status'] == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="anggota.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Anggota</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Anggota</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_anggota as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_anggota']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td>
                                <span class="badge <?php echo $row['status'] == 'Aktif' ? 'badge-success' : 'badge-danger'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_anggota']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_anggota" value="<?php echo $row['id_anggota']; ?>">
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
