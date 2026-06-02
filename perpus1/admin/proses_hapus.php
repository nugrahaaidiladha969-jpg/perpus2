<?php
include '../koneksi.php';

$id   = $_GET['id'];
$buku = $_GET['buku'];

// Kembalikan status buku
mysqli_query($koneksi, 
"UPDATE buku SET status='tersedia' WHERE id_buku='$buku'");

// Hapus transaksi
$hapus = mysqli_query($koneksi, 
"DELETE FROM transaksi WHERE id_transaksi='$id'");

if($hapus){
    echo "<script>
    alert('✅ Data berhasil dihapus');
    window.location.assign('?halaman=data_peminjaman');
    </script>";
}else{
    echo mysqli_error($koneksi);
}
?>