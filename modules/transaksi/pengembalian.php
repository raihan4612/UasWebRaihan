<?php
$page_title = "Transaksi Pengembalian - Perpustakaan Banjarbaru";
include '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $id_pinjam = $_POST['id_pinjam'];
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $buku_ids = $_POST['id_buku'] ?? [];
        $kondisi = $_POST['kondisi'] ?? [];
        
        $sql = "INSERT INTO pengembalian (id_pinjam, tanggal_kembali) VALUES ($id_pinjam, '$tanggal_kembali')";
        if (query($sql)) {
            $id_kembali = mysqli_insert_id($conn);
            
            foreach ($buku_ids as $key => $id_buku) {
                $kond = $kondisi[$key] ?? 'Baik';
                $sql_detail = "INSERT INTO detail_pengembalian (id_kembali, id_buku, kondisi) 
                              VALUES ($id_kembali, $id_buku, '$kond')";
                query($sql_detail);
            }
            $success = "Pengembalian berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan pengembalian!";
        }
    }
}

$sql = "SELECT pg.*, pm.id_anggota, a.nama as nama_anggota, pm.tanggal_pinjam 
        FROM pengembalian pg
        JOIN peminjaman pm ON pg.id_pinjam = pm.id_pinjam
        JOIN anggota a ON pm.id_anggota = a.id_anggota
        ORDER BY pg.id_kembali DESC";
$data = fetchAll(query($sql));

// Get peminjaman yang belum dikembalikan
$peminjaman_list = fetchAll(query("SELECT p.*, a.nama as nama_anggota FROM peminjaman p 
                                   JOIN anggota a ON p.id_anggota = a.id_anggota 
                                   WHERE p.id_pinjam NOT IN (SELECT id_pinjam FROM pengembalian)
                                   ORDER BY p.id_pinjam DESC"));
?>

<div class="container">
    <div class="page-header">
        <h2><i class="fas fa-undo"></i> Transaksi Pengembalian</h2>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="content-box">
        <h3>Tambah Pengembalian Baru</h3>
        <form method="POST">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Peminjaman <span style="color: red;">*</span></label>
                <select name="id_pinjam" class="form-control" required id="selectPeminjaman">
                    <option value="">-- Pilih Peminjaman --</option>
                    <?php foreach ($peminjaman_list as $p): ?>
                        <option value="<?php echo $p['id_pinjam']; ?>">
                            ID: <?php echo $p['id_pinjam']; ?> - <?php echo $p['nama_anggota']; ?> 
                            (Tgl Pinjam: <?php echo date('d/m/Y', strtotime($p['tanggal_pinjam'])); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tanggal Kembali <span style="color: red;">*</span></label>
                <input type="date" name="tanggal_kembali" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            
            <div id="bukuSection"></div>
            
            <div class="flex gap-10">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>

    <div class="content-box mt-20">
        <h3>Daftar Pengembalian</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Kembali</th>
                        <th>ID Pinjam</th>
                        <th>Anggota</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Buku</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($data as $row): 
                        $id_kembali = $row['id_kembali'];
                        $sql_detail = "SELECT b.judul, dk.kondisi FROM detail_pengembalian dk
                                      JOIN buku b ON dk.id_buku = b.id_buku 
                                      WHERE dk.id_kembali = $id_kembali";
                        $detail = fetchAll(query($sql_detail));
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['id_kembali']; ?></td>
                            <td><?php echo $row['id_pinjam']; ?></td>
                            <td><?php echo $row['nama_anggota']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></td>
                            <td>
                                <?php foreach ($detail as $d): ?>
                                    <span class="badge <?php echo $d['kondisi']=='Baik'?'badge-success':'badge-danger'; ?>">
                                        <?php echo $d['judul'] . ' (' . $d['kondisi'] . ')'; ?>
                                    </span><br>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('selectPeminjaman').addEventListener('change', function() {
    var idPinjam = this.value;
    if (idPinjam) {
        fetch('../../includes/get_detail_peminjaman.php?id=' + idPinjam)
            .then(r => r.json())
            .then(data => {
                var html = '<div class="form-group"><label>Kondisi Buku</label><div style="border: 2px solid var(--border-color); border-radius: 8px; padding: 15px;">';
                data.forEach((buku, i) => {
                    html += '<div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">';
                    html += '<strong>' + buku.judul + '</strong>';
                    html += '<input type="hidden" name="id_buku[]" value="' + buku.id_buku + '">';
                    html += '<div style="margin-top: 10px;"><select name="kondisi[]" class="form-control" required>';
                    html += '<option value="Baik">Baik</option>';
                    html += '<option value="Rusak Ringan">Rusak Ringan</option>';
                    html += '<option value="Rusak Berat">Rusak Berat</option>';
                    html += '</select></div></div>';
                });
                html += '</div></div>';
                document.getElementById('bukuSection').innerHTML = html;
            });
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
