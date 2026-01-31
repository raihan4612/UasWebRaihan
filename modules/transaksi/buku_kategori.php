<?php
$page_title = "Buku-Kategori - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $id_buku = $_POST['id_buku'];
        $id_kategori = $_POST['id_kategori'];
        $sql = "INSERT INTO buku_kategori (id_buku, id_kategori) VALUES ($id_buku, $id_kategori)";
        if (query($sql)) $success = "Relasi buku-kategori berhasil ditambahkan!";
        else $error = "Gagal menambahkan relasi!";
    }
    elseif ($_POST['action'] == 'delete') {
        $id = $_POST['id_buku_kategori'];
        $sql = "DELETE FROM buku_kategori WHERE id_buku_kategori=$id";
        if (query($sql)) $success = "Relasi berhasil dihapus!";
        else $error = "Gagal menghapus relasi!";
    }
}

$sql = "SELECT bk.*, b.judul, k.nama_kategori FROM buku_kategori bk
        JOIN buku b ON bk.id_buku = b.id_buku
        JOIN kategori_buku k ON bk.id_kategori = k.id_kategori
        ORDER BY b.judul";
$data = fetchAll(query($sql));

$buku_list = fetchAll(query("SELECT * FROM buku ORDER BY judul"));
$kategori_list = fetchAll(query("SELECT * FROM kategori_buku ORDER BY nama_kategori"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-link"></i> Relasi Buku - Kategori</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3>Tambah Relasi Baru</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
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
                <label>Kategori <span style="color: red;">*</span></label>
                <select name="id_kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?php echo $k['id_kategori']; ?>"><?php echo $k['nama_kategori']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Relasi Buku-Kategori</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['judul']; ?></td>
                            <td><span class="badge badge-success"><?php echo $row['nama_kategori']; ?></span></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id_buku_kategori" value="<?php echo $row['id_buku_kategori']; ?>">
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
