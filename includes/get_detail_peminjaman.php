<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id_pinjam = $_GET['id'];
    $sql = "SELECT b.id_buku, b.judul FROM detail_peminjaman dp 
            JOIN buku b ON dp.id_buku = b.id_buku 
            WHERE dp.id_pinjam = $id_pinjam";
    $result = query($sql);
    $data = fetchAll($result);
    
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
