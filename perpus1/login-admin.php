<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Aplikasi Perpustakaan Sekolah Digital</title>
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
    <div class="vh-100 row justify-content-center align-content-center">
        <form method="post" action="#" class="col-md-3 border p-4 bg-white rounded-4">
            <img src="LGsmk1.png" width="150px" class="mx-auto d-block">
            <h4 class="text-center">Login Admin</h4>
            <h5 class="text-center mb-3">Aplikasi Perpustakaan Sekolah Digital</h5>
            <input type="text" name="username" class="form-control mb-3" placeholder="Masukan Username" required>
           <input type="password" name="password" class="form-control mb-3" placeholder="Masukan Password" required>

            <button name="tombol" type="submit" class="btn btn-success w-100 mb-2">Login</button>
            <a href="login-anggota.php" class="text-decoration-none">Login Sebagai Anggota</a>
       </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['tombol'])){
    session_start();
    include 'koneksi.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $data = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_array($data);

        $_SESSION['id_admin'] = $row['id_admin'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['nama_admin'] = $row['nama_admin'];

        header("Location: admin/dashboard.php");
        exit();
    } else {
        echo "<script>
                alert('❌Maaf login gagal!');
                window.location='login-admin.php';
              </script>";
    }
}
?>