<?php
include '../koneksi.php';
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}
$id = $_GET['id'];
$query_anggota = mysqli_query($koneksi,"SELECT * FROM anggota WHERE id_anggota='$id'");
$data_anggota = mysqli_fetch_array($query_anggota);
?>
<h4>👥 Edit Data Anggota</h4>
<form method="post" action="" class="mt-3">
    <input value="<?=$data_anggota['nis'] ?>" type="number" name="nis" class="form-control mb-2"
    placeholder="Masukan NIS" required>

    <input value="<?=$data_anggota['nama_anggota'] ?>" type="text" name="nama_anggota" class="form-control mb-2"
    placeholder="Masukan Nama Anggota" required>

    <input value="<?=$data_anggota['username'] ?>" type="text" name="username" class="form-control mb-2"
    placeholder="Masukan Username" required>
    <input value="<?=$data_anggota['password'] ?>" type="text" name="password" class="form-control mb-2"
    placeholder="Masukan Password" required>
    <input value="<?=$data_anggota['kelas'] ?>" type="text" name="kelas" class="form-control mb-2"
    placeholder="Masukan Kelas" required>



    <button type="submit" name="tombol" class="btn btn-primary">
        💾 Simpan
    </button>
</form>
<?php
include '../koneksi.php';

if(isset($_POST['tombol'])) {

    $nis = $_POST['nis'];
    $nama_anggota = $_POST['nama_anggota'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $kelas = $_POST['kelas'];

   $query = "UPDATE anggota SET
    nis='$nis',
    nama_anggota='$nama_anggota',
    username='$username',
    password='$password',
    kelas='$kelas'
    WHERE id_anggota='$id'";
    $data = mysqli_query($koneksi, $query);

    if($data){
        echo "<script>
            alert('✅ Data Berhasil Disimpan');
            window.location.assign('?halaman=data_anggota');
        </script>";
    } else {
        echo "<script>
            alert('❌ Data Gagal Disimpan');
            window.location.assign('?halaman=input_anggota');
        </script>";
    }
}
?>
?>