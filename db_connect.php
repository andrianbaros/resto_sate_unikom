<?php
$servername = "localhost";
$username = "wheramye_unikom";
$password = "YYRJ%5.]QD#Z";
$dbname = "wheramye_resto_sate";
// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
