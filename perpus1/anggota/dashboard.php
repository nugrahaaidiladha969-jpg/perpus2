<?php
session_start();

if(empty($_SESSION['id_anggota'])){
    header("location:../login-anggota.php");
    exit();
}

include '../koneksi.php';

// ======================
// 🔔 NOTIFIKASI TELAT
// ======================

$id_anggota = $_SESSION['id_anggota'];

$telat = mysqli_query($koneksi,
"SELECT COUNT(*) AS total 
FROM transaksi 
WHERE id_anggota='$id_anggota'
AND status_transaksi='peminjaman'
AND tgl_pinjam < DATE_SUB(NOW(), INTERVAL 7 DAY)"
);

$data_telat = mysqli_fetch_assoc($telat);
$total_telat = $data_telat['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Anggota | Aplikasi Perpustakaan Sekolah Digital</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body{
            background: #f1f3f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar{
            min-height: 100vh;
            background: linear-gradient(180deg,#198754,#20c997);
            padding-top: 20px;
        }

        .sidebar h4{
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a{
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            margin: 8px 10px;
            border-radius: 12px;
            transition: 0.3s;
            font-weight: 500;
        }

        .sidebar a:hover{
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }

        .content{
            padding: 20px;
        }

        .topbar{
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .main-card{
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .img-container{
            width:100%;
            height:200px;
            overflow:hidden;
            border-radius:10px;
            background:#f0f0f0;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .img-container img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .card-book{
            transition:0.3s;
        }

        .card-book:hover{
            transform:translateY(-5px);
        }

        .book-title{
            font-size:1rem;
            font-weight:bold;
            height:3rem;
            overflow:hidden;
        }
    </style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<div class="col-md-2 sidebar">
    <h4>📚 PERPUS</h4>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="?halaman=history"><i class="bi bi-clock-history"></i> History</a>
    <a href="?halaman=riwayat_pengembalian"><i class="bi bi-check-circle"></i> Riwayat Pengembalian</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="col-md-10 content">

    <div class="topbar">
        <h5>Selamat Datang, <?= $_SESSION['nama_anggota']; ?> 👋</h5>

        <?php if($total_telat > 0): ?>
            <div class="alert alert-danger mt-3">
                🚨 Kamu punya <?= $total_telat; ?> buku yang terlambat dikembalikan!
            </div>
        <?php else: ?>
            <div class="alert alert-success mt-3">
                ✔ Tidak ada buku yang terlambat
            </div>
        <?php endif; ?>
    </div>

    <div class="main-card">

<?php
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : "";
$allowed = [
    'history',
    'riwayat_pengembalian',
    'cari',
    'peminjaman'
];

if(in_array($halaman,$allowed) && file_exists($halaman.".php")){
    include $halaman.".php";
}else{
    include '../koneksi.php';
?>

<form action="?halaman=cari" method="post">
    <label class="text-muted">Yuk cari buku.</label>
    <div class="input-group mb-4">
        <input type="text" name="kunci" class="form-control" placeholder="Masukan judul buku" required>
        <button type="submit" class="btn btn-primary">🔍 Cari</button>
    </div>
</form>

<h4>🛒 Daftar Buku Yang Dipinjam</h4>
<table class="table table-bordered table-hover">
<tr class="fw-bold">
    <td>No</td>
    <td>Judul Buku</td>
    <td>Tanggal Pinjam</td>
    <td>Batas Kembali</td> 
    <td>Status</td>
</tr>

<?php
$no = 1;
$id_anggota = $_SESSION['id_anggota'];

$query = "SELECT * FROM transaksi
JOIN buku ON buku.id_buku = transaksi.id_buku
WHERE transaksi.id_anggota = '$id_anggota'
AND transaksi.status_transaksi = 'peminjaman'
ORDER BY transaksi.id_transaksi DESC";

$data = mysqli_query($koneksi,$query);

foreach($data as $peminjaman){
    $tgl_pinjam = $peminjaman['tgl_pinjam'];
    $batas_kembali = date('Y-m-d H:i:s', strtotime($tgl_pinjam . ' + 7 days'));
    $hari_ini = date('Y-m-d H:i:s');
    $is_telat = (strtotime($hari_ini) > strtotime($batas_kembali));
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $peminjaman['judul_buku']; ?></td>
    <td><?= $peminjaman['tgl_pinjam']; ?></td>
    <td>
        <?php if($is_telat): ?>
            <span class="text-danger fw-bold"><?= $batas_kembali; ?> <small>(Telat!)</small></span>
        <?php else: ?>
            <span class="text-dark"><?= $batas_kembali; ?></span>
        <?php endif; ?>
    </td>
    <td>

<?php if($is_telat): ?>

    <a href="pengembalian.php?id=<?= $peminjaman['id_transaksi']; ?>&buku=<?= $peminjaman['id_buku']; ?>"
       class="btn btn-danger btn-sm">
       ↩ Ajukan Pengembalian
    </a>

<?php else: ?>

    <a href="pengembalian.php?id=<?= $peminjaman['id_transaksi']; ?>&buku=<?= $peminjaman['id_buku']; ?>"
       class="btn btn-success btn-sm">
       ↩ Ajukan Pengembalian
    </a>

<?php endif; ?>

</td>
</tr>
<?php } ?>
</table>

<hr>
<h4 class="mb-4">📚 Daftar Buku</h4>
<div class="row">

<?php
$data_buku = mysqli_query($koneksi,"SELECT * FROM buku ORDER BY id_buku DESC");
foreach($data_buku as $buku){
    // Mengambil nilai stok & penerbit dari database (jika null, otomatis diset default)
    $stok_buku = isset($buku['stok']) ? $buku['stok'] : 0; 
    $penerbit_buku = !empty($buku['penerbit']) ? $buku['penerbit'] : 'Perpustakaan Digital';
?>
<div class="col-md-3 mb-4">
    <div class="card shadow-sm p-3 h-100 border-0 card-book">
        <div class="img-container">
            <?php if(!empty($buku['gambar'])){ ?>
                <img src="../gambar/<?= $buku['gambar']; ?>">
            <?php } else { ?>
                <span class="text-muted">No Cover</span>
            <?php } ?>
        </div>

        <h5 class="book-title mt-3"><?= $buku['judul_buku']; ?></h5>
        <p class="text-muted mb-1"><strong>Pengarang:</strong> <?= $buku['pengarang']; ?></p>
        <p class="text-muted"><strong>Tahun:</strong> <?= $buku['tahun_terbit']; ?></p>

        <!-- 🛠 LOGIKA BARU: Buku tersedia didasarkan pada jumlah stok, bukan text status baku lagi -->
        <?php if($stok_buku > 0){ ?>
            <div class="badge bg-success mb-2 p-2">✅ tersedia (Stok: <?= $stok_buku; ?>)</div>
            
            <a onclick="pinjamModern({
                id_buku: <?= $buku['id_buku']; ?>,
                judul: '<?= addslashes($buku['judul_buku']); ?>',
                pengarang: '<?= addslashes($buku['pengarang']); ?>',
                penerbit: '<?= addslashes($penerbit_buku); ?>',
                tahun: '<?= $buku['tahun_terbit']; ?>',
                gambar: '<?= !empty($buku['gambar']) ? $buku['gambar'] : ''; ?>',
                stok: '<?= $stok_buku; ?>'
            })" class="btn btn-primary w-100">
                🛒 Pinjam
            </a>
        <?php } else { ?>
            <div class="badge bg-danger mb-2 p-2">❌ Habis / Tidak tersedia</div>
            <button class="btn btn-secondary w-100" disabled>❌ Habis</button>
        <?php } ?>
    </div>
</div>
<?php } ?>

</div>
<?php } ?>

    </div>
</div>

</div>
</div>

<script>
function pinjamModern(buku) {
    var gambarUrl = buku.gambar ? '../gambar/' + buku.gambar : 'https://placehold.co/150x200?text=No+Cover';

    Swal.fire({
        title: 'Konfirmasi Peminjaman Buku',
        html: `
            <div style="text-align: center; margin-bottom: 15px;">
                <img src="${gambarUrl}" style="width: 120px; height: 160px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.15);">
            </div>
            <table style="width: 100%; text-align: left; font-size: 14px; border-collapse: collapse;">
                <tr><td style="padding: 5px 0; font-weight: bold; width: 35%;">Judul Buku</td><td style="padding: 5px 0;">: ${buku.judul}</td></tr>
                <tr><td style="padding: 5px 0; font-weight: bold;">Pengarang</td><td style="padding: 5px 0;">: ${buku.pengarang}</td></tr>
                <tr><td style="padding: 5px 0; font-weight: bold;">Penerbit</td><td style="padding: 5px 0;">: ${buku.penerbit}</td></tr>
                <tr><td style="padding: 5px 0; font-weight: bold;">Tahun Terbit</td><td style="padding: 5px 0;">: ${buku.tahun}</td></tr>
                <tr><td style="padding: 5px 0; font-weight: bold;">Tersedia</td><td style="padding: 5px 0;">: <span class="badge bg-info text-dark">${buku.stok} Ekspl</span></td></tr>
            </table>
            <p style="margin-top: 15px; font-weight: 500; color: #198754; text-align: center;">Apakah kamu yakin ingin meminjam buku ini?</p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Pinjam!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?halaman=peminjaman&data=' + buku.id_buku;
        }
    });
}
</script>

</body>
</html>