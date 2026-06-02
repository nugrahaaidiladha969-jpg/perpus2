  <h4>✅Hitory Peminjaman :</h4>
    <table class="table table-bordered">
        <tr class="fw-bold">
            <td>No</td>
            <td>Judul Buku</td>
            <td>Tanggal Pinjam</td>
            <td>Tanggal Kembali</td>
            <td>Status</td>
        </tr>
        <?php
        include'../koneksi.php';
        $no=1;
      $query = "
      SELECT *
      FROM transaksi,buku
      WHERE buku.id_buku=transaksi.id_buku
      AND transaksi.id_anggota='$_SESSION[id_anggota]'
      AND (
      status_transaksi='peminjaman'
      OR status_transaksi='menunggu_pengembalian'
      )";

$data = mysqli_query($koneksi,$query);
        foreach($data as $peminjaman){ ?>
            <tr>
                <td><?= $no++; ?> </td>
                <td><?= $peminjaman['judul_buku'] ?> </td>
                <td><?= $peminjaman['tgl_pinjam'] ?> </td>
                <td><?= $peminjaman['tgl_kembali'] ?> </td>

                <td>
                <?php
                  if($peminjaman['status_transaksi']=='menunggu_pengembalian'){
                  echo "<span class='badge bg-warning'>Menunggu Respon Admin</span>";
             }else{
                  echo "<span class='badge bg-success'>Sudah Disetujui</span>";
              }
              ?>
             </td>
            </tr>
        <?php } ?>
    </table> 