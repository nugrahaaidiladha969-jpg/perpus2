<h4>✅ Riwayat Pengembalian</h4>

<table class="table table-bordered">

<tr class="fw-bold">
    <td>No</td>
    <td>Judul Buku</td>
    <td>Tanggal Pinjam</td>
    <td>Tanggal Kembali</td>
    <td>Denda</td>
</tr>

<?php

include '../koneksi.php';

$no = 1;

$query = "
SELECT *
FROM transaksi
JOIN buku ON buku.id_buku = transaksi.id_buku
WHERE transaksi.id_anggota='$_SESSION[id_anggota]'
AND (
    transaksi.status_transaksi='pengembalian'
    OR transaksi.status_transaksi='selesai'
)
ORDER BY transaksi.id_transaksi DESC
";

$data = mysqli_query($koneksi,$query);

foreach($data as $row){
?>

<tr>

    <td><?= $no++; ?></td>

    <td><?= $row['judul_buku']; ?></td>

    <td><?= $row['tgl_pinjam']; ?></td>

    <td><?= $row['tgl_kembali']; ?></td>

    <td>
        Rp <?= number_format($row['denda'],0,',','.'); ?>
    </td>

</tr>

<?php } ?>

</table>