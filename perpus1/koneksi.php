<?php 
$server = "localhost";
$pengguna = "root";
$password = "";
$database = "perpus1";

$koneksi = mysqli_connect($server, $pengguna, $password, $database);

if (!$koneksi) {
    die("Koneksi error: " . mysqli_connect_error());
}

echo "";
?>