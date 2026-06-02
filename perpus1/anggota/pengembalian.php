<?php

// Memulai session
session_start();

// Menghubungkan file koneksi database
include '../koneksi.php';

// Mengatur zona waktu Indonesia (Jakarta)
date_default_timezone_set("Asia/Jakarta");

// Mengambil id transaksi dari URL
$id = $_GET['id'];

// Mengambil id buku dari URL
$buku = $_GET['buku'];

// Mengambil tanggal dan waktu sekarang
$tgl = date('Y-m-d H:i:s');

// Query update data transaksi menjadi pengembalian
$query = "
UPDATE transaksi 
SET tgl_kembali='$tgl',
status_transaksi='menunggu_pengembalian' 
WHERE id_transaksi='$id'
";

// Menjalankan query update
$data = mysqli_query($koneksi,$query);

// Jika query berhasil dijalankan
if($data){


    // Menampilkan alert lalu pindah ke halaman history
    echo "
    <script>
        alert('📩 Pengajuan pengembalian berhasil dikirim. Menunggu persetujuan admin.');
        window.location.href='dashboard.php?halaman=history';
    </script>
    ";
}
?>