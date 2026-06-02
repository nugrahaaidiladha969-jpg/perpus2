<h4>✅ Data Pengembalian</h4>

<table class="table table-bordered mt-3">

   <tr class="fw-bold">
    <td>No</td>
    <td>NIS</td>
    <td>Nama Anggota</td>
    <td>Judul Buku</td>
    <td>Tanggal Pinjam</td>
    <td>Tanggal Pengembalian</td>
    <td>Denda</td>
    <td>Kelola</td>
</tr>

<?php
include '../koneksi.php';

$no = 1;

$query = "SELECT * FROM transaksi
JOIN buku ON buku.id_buku=transaksi.id_buku
JOIN anggota ON anggota.id_anggota=transaksi.id_anggota
WHERE transaksi.status_transaksi='menunggu_pengembalian'
ORDER BY transaksi.id_transaksi DESC";

$data = mysqli_query($koneksi, $query);

foreach ($data as $peminjaman) { ?>

<tr>

    <td><?= $no++; ?></td>
    <td><?= $peminjaman['nis']; ?></td>
    <td><?= $peminjaman['nama_anggota']; ?></td>
    <td><?= $peminjaman['judul_buku']; ?></td>
    <td><?= $peminjaman['tgl_pinjam']; ?></td>
    <td><?= $peminjaman['tgl_kembali']; ?></td>
    <?php

       $batas_kembali = strtotime($peminjaman['tgl_pinjam'].' +7 days');
       $tgl_kembali = strtotime($peminjaman['tgl_kembali']);

       $terlambat = ceil(($tgl_kembali - $batas_kembali) / 86400);

       if($terlambat < 0){
       $terlambat = 0;
       }

       $denda = $terlambat * 2000;

    ?>
<td>
    Rp <?= number_format($denda,0,',','.'); ?>
</td>

    <td>

<a
onclick="return confirm('Setujui pengembalian buku ini?')"
href="?halaman=proses_pengembalian&id=<?= $peminjaman['id_transaksi']; ?>&buku=<?= $peminjaman['id_buku']; ?>"
class="btn btn-success">

✔ Setujui

</a>

<a
onclick="return confirm('Hapus data ini?')"
class="btn btn-danger"
href="?halaman=proses_hapus&id=<?= $peminjaman['id_transaksi']; ?>&buku=<?= $peminjaman['id_buku']; ?>">

🗑️ Hapus

</a>

</td>

<?php
$pesan = "Konfirmasi penghapusan data transaksi.";

$id_transaksi = $peminjaman['id_transaksi'];
$id_buku = $peminjaman['id_buku'];
?>


    </td>

</tr>

<?php } ?>

</table>

<script>

function hapus(pesan, id_transaksi, id_buku){

    if(confirm(pesan)){

        window.location.href =
        "?halaman=proses_hapus&id=" + id_transaksi +
        "&buku=" + id_buku;

    }

}

</script>