<?php

// Menghubungkan file koneksi database
include '../koneksi.php';

// Mengecek apakah id tersedia di URL
if (!isset($_GET['id'])) {

    // Menampilkan pesan jika id tidak ditemukan
    die("ID tidak ditemukan");
}

// Mengambil id buku dari URL
$id = $_GET['id'];

// Query menghapus data buku berdasarkan id
$data = mysqli_query(
    $koneksi,
    "DELETE FROM buku WHERE id_buku='$id'"
);

// Jika data berhasil dihapus
if($data){

        echo "
        <script>
            alert('✅ Data Berhasil Dihapus');
            window.location.assign('?halaman=data_buku');
        </script>
        ";

    } else {

        // Jika data gagal dihapus
        echo "
        <script>
            alert('❌ Data Gagal Dihapus');
            window.location.assign('?halaman=data_buku');
        </script>
        ";
    }