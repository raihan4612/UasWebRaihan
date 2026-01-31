<?php
$page_title = "Data Buku - Perpustakaan Banjarbaru";
include '../../includes/header.php';

// Handle CRUD Operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $judul = escape($_POST['judul']);
            $id_pengarang = $_POST['id_pengarang'];
            $id_penerbit = $_POST['id_penerbit'];
            $tahun = $_POST['tahun'];
            $jumlah = $_POST['jumlah'];
            
            $sql = "INSERT INTO buku (judul, id_pengarang, id_penerbit, tahun, jumlah) 
                    VALUES ('$judul', $id_pengarang, $id_penerbit, $tahun, $jumlah)";
            if (query($sql)) {
                $success = "Data buku berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan data buku!";
            }
        }
        
        elseif ($action == 'update') {
            $id = $_POST['id_buku'];
            $judul = escape($_POST['judul']);
            $id_pengarang = $_POST['id_pengarang'];
            $id_penerbit = $_POST['id_penerbit'];
            $tahun = $_POST['tahun'];
            $jumlah = $_POST['jumlah'];
            
            $sql = "UPDATE buku SET judul='$judul', id_pengarang=$id_pengarang, 
                    id_penerbit=$id_penerbit, tahun=$tahun, jumlah=$jumlah WHERE id_buku=$id";
            if (query($sql)) {
                $success = "Data buku berhasil diupdate!";
            } else {
                $error = "Gagal mengupdate data buku!";
            }
        }
        
        elseif ($action == 'delete') {
            $id = $_POST['id_buku'];
            $sql = "DELETE FROM buku WHERE id_buku=$id";
            if (query($sql)) {
                $success = "Data buku berhasil dihapus!";
            } else {
                $error = "Gagal menghapus data buku!";
            }
        }
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = query("SELECT * FROM buku WHERE id_buku=$id");
    $edit_data = fetch($result);
}

// Get pengarang dan penerbit untuk dropdown
$pengarang_list = fetchAll(query("SELECT * FROM pengarang ORDER BY nama"));
$penerbit_list = fetchAll(query("SELECT * FROM penerbit ORDER BY nama"));

// Get all data with JOIN
$sql = "SELECT b.*, pg.nama as nama_pengarang, pb.nama as nama_penerbit 
        FROM buku b
        JOIN pengarang pg ON b.id_pengarang = pg.id_pengarang
        JOIN penerbit pb ON b.id_penerbit = pb.id_penerbit
        ORDER BY b.id_buku DESC";
$data_buku = fetchAll(query($sql));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-book"></i> Data Buku</h2>
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
        <h3><?php echo $edit_data ? 'Edit Buku' : 'Tambah Buku Baru'; ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_data ? 'update' : 'create'; ?>">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_buku" value="<?php echo $edit_data['id_buku']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Judul Buku <span style="color: red;">*</span></label>
                <input type="text" name="judul" class="form-control" required 
                       value="<?php echo $edit_data ? $edit_data['judul'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Pengarang <span style="color: red;">*</span></label>
                <select name="id_pengarang" class="form-control" required>
                    <option value="">-- Pilih Pengarang --</option>
                    <?php foreach ($pengarang_list as $pg): ?>
                        <option value="<?php echo $pg['id_pengarang']; ?>" 
                                <?php echo ($edit_data && $edit_data['id_pengarang'] == $pg['id_pengarang']) ? 'selected' : ''; ?>>
                            <?php echo $pg['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Penerbit <span style="color: red;">*</span></label>
                <select name="id_penerbit" class="form-control" required>
                    <option value="">-- Pilih Penerbit --</option>
                    <?php foreach ($penerbit_list as $pb): ?>
                        <option value="<?php echo $pb['id_penerbit']; ?>"
                                <?php echo ($edit_data && $edit_data['id_penerbit'] == $pb['id_penerbit']) ? 'selected' : ''; ?>>
                            <?php echo $pb['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun" class="form-control" min="1900" max="2026"
                       value="<?php echo $edit_data ? $edit_data['tahun'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Jumlah Buku <span style="color: red;">*</span></label>
                <input type="number" name="jumlah" class="form-control" min="0" required
                       value="<?php echo $edit_data ? $edit_data['jumlah'] : '0'; ?>">
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
                </button>
                <?php if ($edit_data): ?>
                    <a href="buku.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Buku</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_buku as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_buku']; ?></td>
                            <td><?php echo $row['judul']; ?></td>
                            <td><?php echo $row['nama_pengarang']; ?></td>
                            <td><?php echo $row['nama_penerbit']; ?></td>
                            <td><?php echo $row['tahun']; ?></td>
                            <td><span class="badge badge-success"><?php echo $row['jumlah']; ?></span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?edit=<?php echo $row['id_buku']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_buku" value="<?php echo $row['id_buku']; ?>">
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
