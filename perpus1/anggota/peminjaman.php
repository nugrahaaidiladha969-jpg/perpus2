<?php
include '../koneksi.php';

date_default_timezone_set("Asia/Jakarta");

$id = $_GET['data'];
$tgl = date('Y-m-d H:i:s');

// 1. Masukkan data ke tabel transaksi
$query = "INSERT INTO transaksi(id_anggota,id_buku,tgl_pinjam,status_transaksi)
VALUES('$_SESSION[id_anggota]','$id','$tgl','peminjaman')";

$data = mysqli_query($koneksi, $query);

if($data){
    // 2. 🛠 PERBAIKAN: Kurangi stok buku sebanyak 1
    mysqli_query($koneksi, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id'");
    
    // 3. ✨ BONUS: Menggunakan SweetAlert2 agar tampilan notifikasi lebih modern
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body { font-family: 'Segoe UI', sans-serif; background: #f1f3f6; }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Berhasil! 🎉',
                text: 'Buku berhasil dipinjam, silakan ambil di ruang perpustakaan.',
                icon: 'success',
                confirmButtonColor: '#198754',
                confirmButtonText: 'Oke'
            }).then((result) => {
                window.location.assign('dashboard.php');
            });
        </script>
    </body>
    </html>
    <?php
}
?>