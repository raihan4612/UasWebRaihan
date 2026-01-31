<?php
$page_title = "Transaksi Peminjaman - Perpustakaan Banjarbaru";
include '../../includes/header.php';

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'create') {
            $id_anggota = $_POST['id_anggota'];
            $id_petugas = $_POST['id_petugas'];
            $tanggal_pinjam = $_POST['tanggal_pinjam'];
            $tanggal_harus_kembali = $_POST['tanggal_harus_kembali'];
            $buku_ids = isset($_POST['id_buku']) ? $_POST['id_buku'] : [];
            
            // Insert peminjaman
            $sql = "INSERT INTO peminjaman (id_anggota, id_petugas, tanggal_pinjam, tanggal_harus_kembali) 
                    VALUES ($id_anggota, $id_petugas, '$tanggal_pinjam', '$tanggal_harus_kembali')";
            if (query($sql)) {
                $id_pinjam = mysqli_insert_id($conn);
                
                // Insert detail peminjaman
                foreach ($buku_ids as $id_buku) {
                    $sql_detail = "INSERT INTO detail_peminjaman (id_pinjam, id_buku) VALUES ($id_pinjam, $id_buku)";
                    query($sql_detail);
                }
                
                $success = "Transaksi peminjaman berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan transaksi peminjaman!";
            }
        }
        
        elseif ($action == 'delete') {
            $id = $_POST['id_pinjam'];
            $sql = "DELETE FROM peminjaman WHERE id_pinjam=$id";
            if (query($sql)) {
                $success = "Transaksi peminjaman berhasil dihapus!";
            } else {
                $error = "Gagal menghapus transaksi peminjaman!";
            }
        }
    }
}

// Get all data with JOIN
$sql = "SELECT p.*, a.nama as nama_anggota, pt.nama as nama_petugas 
        FROM peminjaman p
        JOIN anggota a ON p.id_anggota = a.id_anggota
        JOIN petugas pt ON p.id_petugas = pt.id_petugas
        ORDER BY p.id_pinjam DESC";
$data_peminjaman = fetchAll(query($sql));

// Get anggota, petugas, dan buku untuk form
$anggota_list = fetchAll(query("SELECT * FROM anggota WHERE status='Aktif' ORDER BY nama"));
$petugas_list = fetchAll(query("SELECT * FROM petugas ORDER BY nama"));
$buku_list = fetchAll(query("SELECT id_buku, judul, jumlah FROM buku WHERE jumlah > 0 ORDER BY judul"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-hand-holding"></i> Transaksi Peminjaman</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3>Tambah Peminjaman Baru</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Anggota <span style="color: red;">*</span></label>
                <select name="id_anggota" class="form-control" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php foreach ($anggota_list as $a): ?>
                        <option value="<?php echo $a['id_anggota']; ?>">
                            <?php echo $a['id_anggota'] . ' - ' . $a['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Petugas <span style="color: red;">*</span></label>
                <select name="id_petugas" class="form-control" required>
                    <option value="">-- Pilih Petugas --</option>
                    <?php foreach ($petugas_list as $p): ?>
                        <option value="<?php echo $p['id_petugas']; ?>">
                            <?php echo $p['nama']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tanggal Pinjam <span style="color: red;">*</span></label>
                <input type="date" name="tanggal_pinjam" class="form-control" required 
                       value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label>Tanggal Harus Kembali <span style="color: red;">*</span></label>
                <input type="date" name="tanggal_harus_kembali" class="form-control" required 
                       value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
            </div>
            
            <div class="form-group">
                <label>Pilih Buku <span style="color: red;">*</span></label>
                <div style="border: 2px solid var(--border-color); border-radius: 8px; padding: 15px; max-height: 200px; overflow-y: auto;">
                    <?php foreach ($buku_list as $b): ?>
                        <div style="margin-bottom: 10px;">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" name="id_buku[]" value="<?php echo $b['id_buku']; ?>" 
                                       style="margin-right: 10px; width: 18px; height: 18px;">
                                <span><?php echo $b['judul'] . ' (Stok: ' . $b['jumlah'] . ')'; ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Peminjaman</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pinjam</th>
                        <th>Anggota</th>
                        <th>Petugas</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Harus Kembali</th>
                        <th>Buku</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data_peminjaman as $row): 
                        // Get detail buku
                        $id_pinjam = $row['id_pinjam'];
                        $sql_detail = "SELECT b.judul FROM detail_peminjaman dp 
                                      JOIN buku b ON dp.id_buku = b.id_buku 
                                      WHERE dp.id_pinjam = $id_pinjam";
                        $detail_buku = fetchAll(query($sql_detail));
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_pinjam']; ?></td>
                            <td><?php echo $row['nama_anggota']; ?></td>
                            <td><?php echo $row['nama_petugas']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_harus_kembali'])); ?></td>
                            <td>
                                <?php foreach ($detail_buku as $buku): ?>
                                    <span class="badge badge-success"><?php echo $buku['judul']; ?></span><br>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_pinjam" value="<?php echo $row['id_pinjam']; ?>">
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
