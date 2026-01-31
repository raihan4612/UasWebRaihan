<?php
$page_title = "Data Petugas - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $nama = escape($_POST['nama']);
            $jabatan = escape($_POST['jabatan']);
            $no_hp = escape($_POST['no_hp']);
            $sql = "INSERT INTO petugas (nama, jabatan, no_hp) VALUES ('$nama', '$jabatan', '$no_hp')";
            if (query($sql)) $success = "Data petugas berhasil ditambahkan!";
            else $error = "Gagal menambahkan data petugas!";
        }
        elseif ($action == 'update') {
            $id = $_POST['id_petugas'];
            $nama = escape($_POST['nama']);
            $jabatan = escape($_POST['jabatan']);
            $no_hp = escape($_POST['no_hp']);
            $sql = "UPDATE petugas SET nama='$nama', jabatan='$jabatan', no_hp='$no_hp' WHERE id_petugas=$id";
            if (query($sql)) $success = "Data petugas berhasil diupdate!";
            else $error = "Gagal mengupdate data petugas!";
        }
        elseif ($action == 'delete') {
            $id = $_POST['id_petugas'];
            $sql = "DELETE FROM petugas WHERE id_petugas=$id";
            if (query($sql)) $success = "Data petugas berhasil dihapus!";
            else $error = "Gagal menghapus data petugas!";
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM petugas WHERE id_petugas=$id");
    $edit_data = fetch($result);
}

$data = fetchAll(query("SELECT * FROM petugas ORDER BY nama"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-user-tie"></i> Data Petugas</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Petugas' : 'Tambah Petugas Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_petugas" value="<?php echo $edit_data['id_petugas']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Petugas <span style="color: red;">*</span></label>
                <input type="text" name="nama" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Jabatan</label>
                <input type="text" name="jabatan" class="form-control" 
                       value="<?php echo $edit_data ? $edit_data['jabatan'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" 
                       value="<?php echo $edit_data ? $edit_data['no_hp'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="petugas.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Petugas</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>No. HP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_petugas']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['jabatan']; ?></td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_petugas']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_petugas" value="<?php echo $row['id_petugas']; ?>">
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
