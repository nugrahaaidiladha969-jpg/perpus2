<?php
if (!isset($_GET['id']) || !isset($_GET['buku'])) {
    die("Data tidak valid");
}

$id = $_GET['id'];
$buku = $_GET['buku'];

date_default_timezone_set("Asia/Jakarta");
$tgl_kembali = date('Y-m-d H:i:s');

include '../koneksi.php';

$data = mysqli_query($koneksi,
"UPDATE transaksi SET 
status_transaksi='selesai',
tgl_kembali='$tgl_kembali' 
WHERE id_transaksi='$id'");

if($data){

    mysqli_query($koneksi,
    "UPDATE buku
    SET stok = stok + 1,
    status='tersedia'
    WHERE id_buku='$buku'");

    echo "<script>
    alert('✅ Buku berhasil dikembalikan');
    window.location.assign('?halaman=data_peminjaman');
    </script>";

} else {
    echo mysqli_error($koneksi);
}
?>