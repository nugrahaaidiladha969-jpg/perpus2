<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Anggota - Aplikasi Perpustakaan Sekolah Digital</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
    body {
    }

    form {
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    img {
        margin-bottom: 10px;
        transition: 0.3s;
    }

    img:hover {
        transform: scale(1.05) rotate(2deg);
    }

    h4 {
        font-weight: bold;
        color: #198754;
    }

    h5 {
        font-size: 14px;
        color: #666;
    }

    input.form-control {
        border-radius: 12px;
        padding: 12px;
        border: 1px solid #ddd;
        transition: 0.3s;
    }

    input.form-control:focus {
        border-color: #20c997;
        box-shadow: 0 0 8px rgba(32,201,151,0.6);
        transform: scale(1.02);
    }

    button.btn-success {
        border-radius: 12px;
        background: linear-gradient(135deg, #198754, #20c997);
        border: none;
        font-weight: bold;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    button.btn-success:hover {
        background: linear-gradient(135deg, #20c997, #198754);
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    a {
        display: block;
        text-align: center;
        color: #198754;
        font-size: 14px;
        margin-top: 5px;
        transition: 0.2s;
    }

    a:hover {
        text-decoration: underline;
        color: #20c997;
    }
</style>

</head>
<body class="bg-light">
    <div class="vh-100 row justify-content-center align-content-center m-0">
        <form method="post" action="#" class="col-md-3 border p-4 bg-white rounded-4">
            <img src="LGsmk1.png" width="120px" class="mx-auto d-block">
            <h4 class="text-center">Pendaftaran Anggota</h4>
            <h5 class="text-center mb-4">Aplikasi Perpustakaan Sekolah Digital</h5>
            
            <input type="text" name="nis" class="form-control mb-3" placeholder="Masukan NIS" required>
            <input type="text" name="nama_anggota" class="form-control mb-3" placeholder="Masukan Nama Anggota" required>
            <input type="text" name="username" class="form-control mb-3" placeholder="Masukan Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Masukan Password" required>
            <input type="text" name="kelas" class="form-control mb-3" placeholder="Masukan Kelas" required>

            <button name="tombol" type="submit" class="btn btn-success w-100 mb-3">
                Daftar Sekarang
            </button>

            <div class="text-center mt-2">
                <a href="login-anggota.php" class="text-decoration-none d-block">
                    Sudah punya akun? Login sebagai Anggota
                </a>
                <a href="login-admin.php" class="text-decoration-none d-block mt-2">
                    Login sebagai Admin
                </a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
// Bagian PHP tetap sama, tidak ada perubahan logic
if (isset($_POST['tombol'])){
    include 'koneksi.php';

    $nis = $_POST['nis'];
    $nama_anggota = $_POST['nama_anggota'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $kelas = $_POST['kelas'];

    $query = "INSERT INTO anggota(nis,nama_anggota,username,password,kelas) VALUES
    ('$nis','$nama_anggota','$username','$pass','$kelas')";
    
    $data = mysqli_query($koneksi, $query);

    if($data){
        session_start();

        $_SESSION['id_anggota'] = mysqli_insert_id($koneksi);
        $_SESSION['username'] = $username;
        $_SESSION['nama_anggota'] = $nama_anggota;

        header("Location: anggota/dashboard.php");
        exit();
    } else {
        echo mysqli_error($koneksi);
    }
}
?>