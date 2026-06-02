<?php
$kunci = isset($_POST['kunci']) ? mysqli_real_escape_string($koneksi, $_POST['kunci']) : '';
?>
<form action="?halaman=cari" method="post">
    <label class="text-muted">Yuk cari buku.</label>
    <div class="input-group mb-4">
        <input type="text" name="kunci" class="form-control" value="<?= htmlspecialchars($kunci) ?>" required placeholder="Masukan judul buku">
        <button type="submit" class="btn btn-primary">🔍 Cari</button>
    </div>
</form>

<h4>🔍 Pencarian Buku "<?= htmlspecialchars($kunci) ?>"</h4>

<div class="row">

<?php
include '../koneksi.php';

// Jalankan query pencarian jika ada kata kunci
if (!empty($kunci)) {
    $data_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE judul_buku LIKE '%$kunci%' ORDER BY id_buku DESC");

    if (mysqli_num_rows($data_buku) > 0) {
        foreach($data_buku as $buku){ 
            // Mengambil nilai stok & penerbit (jika null/kosong, otomatis diset default)
            $stok_buku = isset($buku['stok']) ? $buku['stok'] : 0; 
            $penerbit_buku = !empty($buku['penerbit']) ? $buku['penerbit'] : 'Perpustakaan Digital';
        ?>
            
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm p-3 h-100 border-0 card-book">
                <!-- 📸 1. TAMBAHAN: Kontainer Foto Buku -->
                <div class="img-container">
                    <?php if(!empty($buku['gambar'])){ ?>
                        <img src="../gambar/<?= $buku['gambar']; ?>">
                    <?php } else { ?>
                        <span class="text-muted">No Cover</span>
                    <?php } ?>
                </div>

                <h5 class="book-title mt-3"><?= $buku['judul_buku'] ?></h5>
                <p class="text-muted mb-1"><strong>Pengarang:</strong> <?= $buku['pengarang'] ?></p>
                <p class="text-muted mb-3"><strong>Diterbitkan tahun:</strong> <?= $buku['tahun_terbit'] ?></p>

                <!-- 🛠 2. LOGIKA BARU: Cek berdasarkan jumlah stok -->
                <?php if($stok_buku > 0){ ?>
                    <div class="badge bg-success mb-2 p-2">✅ tersedia (Stok: <?= $stok_buku; ?>)</div>
                    
                    <!-- 🔗 Mengarahkan ke fungsi SweetAlert2 pinjamModern yang ada di dashboard -->
                    <a onclick="pinjamModern({
                        id_buku: <?= $buku['id_buku']; ?>,
                        judul: '<?= addslashes($buku['judul_buku']); ?>',
                        pengarang: '<?= addslashes($buku['pengarang']); ?>',
                        penerbit: '<?= addslashes($penerbit_buku); ?>',
                        tahun: '<?= $buku['tahun_terbit']; ?>',
                        gambar: '<?= !empty($buku['gambar']) ? $buku['gambar'] : ''; ?>',
                        stok: '<?= $stok_buku; ?>'
                    })" class="btn btn-primary w-100">🛒 Pinjam</a>

                <?php } else { ?>
                    <div class="badge bg-danger mb-2 p-2">❌ Habis / Tidak tersedia</div>
                    <button class="btn btn-secondary w-100" disabled>❌ Habis</button>
                <?php } ?>

            </div>
        </div>

        <?php 
        } 
    } else {
        echo "<div class='col-12'><div class='alert alert-warning'>📚 Buku dengan judul '$kunci' tidak ditemukan.</div></div>";
    }
} else {
    echo "<div class='col-12'><div class='alert alert-info'>Silakan ketik judul buku pada form di atas untuk mulai mencari.</div></div>";
}
?>

</div>