<?php
include '../koneksi.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}
$id = $_GET['id'];
$data = mysqli_query($koneksi,"DELETE FROM buku WHERE id_buku='$id'");
if($data){
        echo "<script>
            alert('✅ Data Berhasil Dihapus');
            window.location.assign('?halaman=data_buku');
        </script>";
    } else {
        echo "<script>
            alert('❌ Data Gagal Dihapus');
            window.location.assign('?halaman=data_buku');
        </script>";
    }