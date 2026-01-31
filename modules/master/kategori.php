<?php
$page_title = "Data Kategori Buku - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $nama_kategori = escape($_POST['nama_kategori']);
            $sql = "INSERT INTO kategori_buku (nama_kategori) VALUES ('$nama_kategori')";
            if (query($sql)) $success = "Data kategori berhasil ditambahkan!";
            else $error = "Gagal menambahkan data kategori!";
        }
        elseif ($action == 'update') {
            $id = $_POST['id_kategori'];
            $nama_kategori = escape($_POST['nama_kategori']);
            $sql = "UPDATE kategori_buku SET nama_kategori='$nama_kategori' WHERE id_kategori=$id";
            if (query($sql)) $success = "Data kategori berhasil diupdate!";
            else $error = "Gagal mengupdate data kategori!";
        }
        elseif ($action == 'delete') {
            $id = $_POST['id_kategori'];
            $sql = "DELETE FROM kategori_buku WHERE id_kategori=$id";
            if (query($sql)) $success = "Data kategori berhasil dihapus!";
            else $error = "Gagal menghapus data kategori!";
        }
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM kategori_buku WHERE id_kategori=$id");
    $edit_data = fetch($result);
}

$data_kategori = fetchAll(query("SELECT * FROM kategori_buku ORDER BY nama_kategori"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-tags"></i> Data Kategori Buku</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3><?php echo $edit_data ? 'Edit Kategori' : 'Tambah Kategori Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_kategori" value="<?php echo $edit_data['id_kategori']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Kategori <span style="color: red;">*</span></label>
                <input type="text" name="nama_kategori" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['nama_kategori'] : ''; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="kategori.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Kategori</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_kategori as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_kategori']; ?></td>
                            <td><?php echo $row['nama_kategori']; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_kategori']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_kategori" value="<?php echo $row['id_kategori']; ?>">
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
