<?php
session_start();

if(empty($_SESSION['id_admin'])){
    header("location:../login-admin.php");
    exit();
}

include '../koneksi.php';

// ======================
// STATISTIK OTOMATIS (AMAN)
// ======================

// Total Buku
$buku = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM buku");
$total_buku = mysqli_fetch_assoc($buku)['total'] ?? 0;

// Total Anggota
$anggota = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM anggota");
$total_anggota = mysqli_fetch_assoc($anggota)['total'] ?? 0;

// Total Peminjaman (ANTI ERROR)
$total_pinjam = 0;

$q = mysqli_query($koneksi,
"SELECT COUNT(*) AS total 
FROM transaksi
WHERE status_transaksi='peminjaman'"
);

if($q){
    $data = mysqli_fetch_assoc($q);
    $total_pinjam = $data['total'] ?? 0;
}

// ======================
// 🔥 NOTIFIKASI TELAT (PROSES BACKGROUND TETAP JALAN)
// ======================
$total_telat = 0;

$cek_telat = mysqli_query($koneksi,
"SELECT COUNT(*) AS total 
FROM transaksi 
WHERE status_transaksi='peminjaman' 
AND tgl_pinjam < DATE_SUB(NOW(), INTERVAL 7 DAY)"
);

if($cek_telat){
    $data_telat = mysqli_fetch_assoc($cek_telat);
    $total_telat = $data_telat['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin | Aplikasi Perpustakaan Sekolah Digital</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

        .card-box{
            border-radius: 18px;
            padding: 20px;
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
         cursor: pointer;
        }

        .card-box:hover{
            transform: translateY(-5px);
        }

        .bg1{
            background: linear-gradient(135deg,#198754,#20c997);
        }

        .bg2{
            background: linear-gradient(135deg,#0d6efd,#4dabf7);
        }

        .bg3{
            background: linear-gradient(135deg,#fd7e14,#ffc107);
        }

        .main-card{
            background: white;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow-x: auto; /* 🛠 TAMBAHAN: Mencegah tabel merusak layout jika layar sempit */
        }

        /* 🛠 BARU: Memaksa kolom tindakan tombol Kelola agar tetap sejajar horizontal di semua browser */
        table td .btn, table td a.btn {
            display: inline-inline;
            white-space: nowrap; 
        }
        
        table th:last-child, table td:last-child {
            min-width: 160px !important;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 sidebar">

            <h4>📚 PERPUS</h4>

            <a href="dashboard.php">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="?halaman=data_buku">
                <i class="bi bi-book"></i> Buku
            </a>

            <a href="?halaman=data_anggota">
                <i class="bi bi-people"></i> Anggota
            </a>

            <a href="?halaman=data_peminjaman">
                <i class="bi bi-journal-check"></i> Peminjaman
            </a>

            <a href="?halaman=data_pengembalian">
                <i class="bi bi-check2-square"></i> Pengembalian
            </a>

            <a href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>

        </div>

        <div class="col-md-10 content">

            <div class="topbar">
                <h5>
                    Selamat Datang,
                    <?= $_SESSION['nama_admin']; ?> 👋
                </h5>
            </div>

<?php if(empty($_GET['halaman'])) { ?>

           <div class="row mb-4">

    <div class="col-md-4 mb-3">

        <a href="?halaman=data_buku"
        style="text-decoration:none;">

            <div class="card-box bg1">

                <h3>📚</h3>

                <h5>Total Buku</h5>

                <h2><?= $total_buku; ?></h2>

            </div>

        </a>

    </div>

    <div class="col-md-4 mb-3">

        <a href="?halaman=data_anggota"
        style="text-decoration:none;">

            <div class="card-box bg2">

                <h3>👨‍🎓</h3>

                <h5>Total Anggota</h5>

                <h2><?= $total_anggota; ?></h2>

            </div>

        </a>

    </div>

    <div class="col-md-4 mb-3">

        <a href="?halaman=data_peminjaman"
        style="text-decoration:none;">

            <div class="card-box bg3">

                <h3>📖</h3>

                <h5>Peminjaman</h5>

                <h2><?= $total_pinjam; ?></h2>

            </div>

        </a>

    </div>

</div>

<?php } ?>
            <div class="main-card">

                <?php

                $halaman = isset($_GET['halaman']) ? $_GET['halaman'] : "";

                $allowed = [

                    'data_buku',
                    'input_buku',
                    'edit_buku',
                    'hapus_buku',

                    'data_anggota',
                    'input_anggota',
                    'edit_anggota',
                    'hapus_anggota',

                    'data_peminjaman',
                    'input_peminjaman',
                    'data_pengembalian',
                    'input_pengembalian',
                    'proses_pengembalian',
                    'proses_hapus',

                ];

                if(in_array($halaman, $allowed) && file_exists($halaman.".php")){
                    include $halaman.".php";
                } else {
                ?>

                    <h4>Dashboard Admin</h4>

                    <p class="text-muted">
                        Aplikasi Perpustakaan Sekolah Digital membantu
                        pengelolaan data buku, anggota dan peminjaman
                        secara cepat dan terorganisir.
                    </p>

                <?php } ?>

            </div>

        </div>

    </div>
</div>

</body>
</html>