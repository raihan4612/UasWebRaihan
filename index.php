<?php
$page_title = "Home - Perpustakaan Banjarbaru";
include 'includes/header.php';

// Get statistics with error handling
try {
    $result = query("SELECT COUNT(*) as total FROM anggota WHERE status='Aktif'");
    $total_anggota = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT SUM(jumlah) as total FROM buku");
    $total_buku = $result ? (mysqli_fetch_assoc($result)['total'] ?? 0) : 0;
    
    $result = query("SELECT COUNT(*) as total FROM peminjaman");
    $total_peminjaman = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT COUNT(*) as total FROM pengembalian");
    $total_pengembalian = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT COUNT(*) as total FROM kategori_buku");
    $total_kategori = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT COUNT(*) as total FROM pengarang");
    $total_pengarang = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT COUNT(*) as total FROM penerbit");
    $total_penerbit = $result ? mysqli_fetch_assoc($result)['total'] : 0;
    
    $result = query("SELECT COUNT(*) as total FROM denda");
    $total_denda = $result ? mysqli_fetch_assoc($result)['total'] : 0;
} catch (Exception $e) {
    // Set default values jika terjadi error
    $total_anggota = 0;
    $total_buku = 0;
    $total_peminjaman = 0;
    $total_pengembalian = 0;
    $total_kategori = 0;
    $total_pengarang = 0;
    $total_penerbit = 0;
    $total_denda = 0;
}
?>

<section class="hero">
    <div class="hero-content">
        <h1>Selamat Datang di Perpustakaan Banjarbaru</h1>
        <p>Pusat Informasi dan Literasi untuk Masyarakat Banjarbaru. Kami menyediakan berbagai koleksi buku, majalah, dan sumber pembelajaran untuk meningkatkan pengetahuan dan wawasan masyarakat. Bergabunglah dengan kami dalam membangun budaya membaca yang lebih baik.</p>
        <a href="modules/master/anggota.php" class="hero-btn">
            <i class="fas fa-user-plus"></i> Daftar Sekarang
        </a>
    </div>
</section>

<div class="dashboard">
    <div class="dashboard-grid">
        <!-- Anggota Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-content">
                <h3>Anggota Aktif</h3>
                <div class="count"><?php echo $total_anggota; ?></div>
                <div class="label">Total Anggota Terdaftar</div>
                <a href="modules/master/anggota.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Buku Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-content">
                <h3>Koleksi Buku</h3>
                <div class="count"><?php echo $total_buku; ?></div>
                <div class="label">Total Buku Tersedia</div>
                <a href="modules/master/buku.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Peminjaman Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-hand-holding"></i>
            </div>
            <div class="card-content">
                <h3>Peminjaman</h3>
                <div class="count"><?php echo $total_peminjaman; ?></div>
                <div class="label">Total Transaksi Peminjaman</div>
                <a href="modules/transaksi/peminjaman.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Pengembalian Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-undo"></i>
            </div>
            <div class="card-content">
                <h3>Pengembalian</h3>
                <div class="count"><?php echo $total_pengembalian; ?></div>
                <div class="label">Total Buku Dikembalikan</div>
                <a href="modules/transaksi/pengembalian.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Kategori Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="card-content">
                <h3>Kategori Buku</h3>
                <div class="count"><?php echo $total_kategori; ?></div>
                <div class="label">Total Kategori Tersedia</div>
                <a href="modules/master/kategori.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Pengarang Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-pen-fancy"></i>
            </div>
            <div class="card-content">
                <h3>Pengarang</h3>
                <div class="count"><?php echo $total_pengarang; ?></div>
                <div class="label">Total Pengarang Terdaftar</div>
                <a href="modules/master/pengarang.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Penerbit Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="card-content">
                <h3>Penerbit</h3>
                <div class="count"><?php echo $total_penerbit; ?></div>
                <div class="label">Total Penerbit Terdaftar</div>
                <a href="modules/master/penerbit.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Denda Card -->
        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="card-content">
                <h3>Denda</h3>
                <div class="count"><?php echo $total_denda; ?></div>
                <div class="label">Total Transaksi Denda</div>
                <a href="modules/transaksi/denda.php" class="card-link">
                    Lihat Detail <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
