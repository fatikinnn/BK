<?php 
// mengaktifkan session php
session_start();
 
// menghubungkan dengan koneksi
include '../koneksi.php';
 
// menangkap data yang dikirim dari form
$nip = $_POST['nip'];
$password = $_POST['password'];
 
// menyeleksi data admin dengan username dan password yang sesuai
$data = mysqli_query($koneksi, "SELECT * FROM dokter WHERE nip='$nip' AND password='$password'");
 
// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);
 
if($cek > 0){
    $row = mysqli_fetch_assoc($data);
    $_SESSION['id'] = $row['id']; // Menyimpan ID dokter ke dalam sesi
	$_SESSION['nip'] = $nip;
	$_SESSION['status'] = "login";
	header("location: dokter.php");
}else{
    $_SESSION["login_error"] = "Kombinasi NIP dan Password tidak valid.";
    header("Location: login.php");
    exit();
}
?>
