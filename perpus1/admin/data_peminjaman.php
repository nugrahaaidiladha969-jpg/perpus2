<h4>🛒 Data Peminjaman</h4>

<a href="?halaman=input_peminjaman" class="btn btn-secondary">
   ➕ Tambah Data Peminjaman
</a>

<table class="table table-bordered mt-3">
    <tr class="fw-bold">
        <td>No</td>
        <td>NIS</td>
        <td>Nama Anggota</td>
        <td>Judul Buku</td>
        <td>Tanggal Pinjam</td>
        <td>Kelola</td>
    </tr>

<?php
include '../koneksi.php';

$no = 1;

$query = "SELECT * FROM transaksi
JOIN buku ON buku.id_buku=transaksi.id_buku
JOIN anggota ON anggota.id_anggota=transaksi.id_anggota
WHERE transaksi.status_transaksi='peminjaman'
ORDER BY transaksi.id_transaksi DESC";

$data = mysqli_query($koneksi, $query);

foreach ($data as $peminjaman) { ?>

<tr>
    <td><?= $no++; ?></td>
    <td><?= $peminjaman['nis']; ?></td>
    <td><?= $peminjaman['nama_anggota']; ?></td>
    <td><?= $peminjaman['judul_buku']; ?></td>
    <td><?= $peminjaman['tgl_pinjam']; ?></td>

    <td>

<?php
$pesan = "✅ Konfirmasi pengembalian buku. {$peminjaman['nama_anggota']}, buku {$peminjaman['judul_buku']}";

$id_transaksi = $peminjaman['id_transaksi'];
$id_buku = $peminjaman['id_buku'];
?>

<a onclick="pengembalian('<?= $pesan ?>', <?= $id_transaksi ?>, <?= $id_buku ?>)" 
class="btn btn-success">
✅ Pengembalian
</a>

<?php
$pesan = "Konfirmasi penghapusan data transaksi. {$peminjaman['nama_anggota']}, buku {$peminjaman['judul_buku']}";
?>

<a onclick="hapus('<?= $pesan ?>', <?= $id_transaksi ?>, <?= $id_buku ?>)" 
class="btn btn-danger">
🗑️ Hapus
</a>

    </td>
</tr>

<?php } ?>

</table>

<script>

function pengembalian(pesan, id_transaksi, id_buku){

    if(confirm(pesan + "\n\nApakah Anda yakin ingin memproses pengembalian buku ini?")){

        window.location.href =
        "?halaman=proses_pengembalian&id=" + id_transaksi +
        "&buku=" + id_buku;

    }

}

function hapus(pesan, id_transaksi, id_buku){

    if(confirm(pesan + "\n\nData yang dihapus tidak dapat dikembalikan. Lanjutkan?")){

        window.location.href =
        "?halaman=proses_hapus&id=" + id_transaksi +
        "&buku=" + id_buku;

    }

}

</script>