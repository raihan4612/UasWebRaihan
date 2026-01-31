<?php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Perpustakaan Banjarbaru'; ?></title>
    <?php
    // Menentukan base path untuk CSS
    $css_path = '';
    $current_dir = dirname($_SERVER['PHP_SELF']);
    if (strpos($current_dir, '/modules/') !== false) {
        $css_path = '../../';
    }
    ?>
    <link rel="stylesheet" href="<?php echo $css_path; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-book-open"></i>
                <span>Perpustakaan Banjarbaru</span>
            </div>
            <ul class="nav-menu">
                <?php
                // Menentukan base path untuk navigasi
                $base_path = '';
                $current_dir = dirname($_SERVER['PHP_SELF']);
                if (strpos($current_dir, '/modules/') !== false) {
                    $base_path = '../../';
                }
                ?>
                <li><a href="<?php echo $base_path; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i> Home
                </a></li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-database"></i> Master Data <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?php echo $base_path; ?>modules/master/anggota.php"><i class="fas fa-users"></i> Anggota</a>
                        <a href="<?php echo $base_path; ?>modules/master/buku.php"><i class="fas fa-book"></i> Buku</a>
                        <a href="<?php echo $base_path; ?>modules/master/kategori.php"><i class="fas fa-tags"></i> Kategori Buku</a>
                        <a href="<?php echo $base_path; ?>modules/master/pengarang.php"><i class="fas fa-pen-fancy"></i> Pengarang</a>
                        <a href="<?php echo $base_path; ?>modules/master/penerbit.php"><i class="fas fa-building"></i> Penerbit</a>
                        <a href="<?php echo $base_path; ?>modules/master/petugas.php"><i class="fas fa-user-tie"></i> Petugas</a>
                        <a href="<?php echo $base_path; ?>modules/master/rak.php"><i class="fas fa-warehouse"></i> Rak</a>
                        <a href="<?php echo $base_path; ?>modules/master/tarif_denda.php"><i class="fas fa-money-bill"></i> Tarif Denda</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-exchange-alt"></i> Transaksi <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?php echo $base_path; ?>modules/transaksi/peminjaman.php"><i class="fas fa-hand-holding"></i> Peminjaman</a>
                        <a href="<?php echo $base_path; ?>modules/transaksi/pengembalian.php"><i class="fas fa-undo"></i> Pengembalian</a>
                        <a href="<?php echo $base_path; ?>modules/transaksi/buku_kategori.php"><i class="fas fa-link"></i> Buku-Kategori</a>
                        <a href="<?php echo $base_path; ?>modules/transaksi/buku_rak.php"><i class="fas fa-boxes"></i> Buku-Rak</a>
                        <a href="<?php echo $base_path; ?>modules/transaksi/denda.php"><i class="fas fa-receipt"></i> Denda</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-file-alt"></i> Laporan <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="dropdown-content">
                        <a href="<?php echo $base_path; ?>modules/laporan/laporan_peminjaman.php"><i class="fas fa-chart-line"></i> Laporan Peminjaman</a>
                        <a href="<?php echo $base_path; ?>modules/laporan/laporan_denda.php"><i class="fas fa-exclamation-triangle"></i> Laporan Denda</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
