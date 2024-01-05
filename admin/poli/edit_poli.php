<?php
// Mulai sesi
session_start();

// Include the database connection file
include_once("../../koneksi.php");

// Mengecek apakah sesi 'username' dan 'status' diatur
if (!isset($_SESSION['username']) || $_SESSION['status'] !== "login") {
    // Jika sesi tidak diatur atau status bukan "login", arahkan pengguna ke halaman login
    $_SESSION["login_error"] = "Anda harus login terlebih dahulu.";
    header("Location: login.php");
    exit();
}

// Mengecek apakah parameter id diatur
if (!isset($_GET['id'])) {
    // Jika tidak, arahkan pengguna ke halaman daftar poli
    header("Location: poli.php");
    exit();
}

$id_poli = $_GET['id'];

// Query untuk mendapatkan data poli berdasarkan ID
$query_get_poli = "SELECT * FROM poli WHERE id = '$id_poli'";
$result_get_poli = mysqli_query($koneksi, $query_get_poli);
$row_poli = mysqli_fetch_assoc($result_get_poli);

// Proses update poli
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    $query_update_poli = "UPDATE poli SET nama_poli='$nama_poli', keterangan='$keterangan' WHERE id='$id_poli'";

    if (mysqli_query($koneksi, $query_update_poli)) {
        // Redirect to the list of poli after successful update
        header("Location: poli.php");
        exit();
    } else {
        echo "Error: " . $query_update_poli . "<br>" . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Poli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

</head>
<body>
    <h2 class="text-center mb-4">Edit Poli</h2>

    <!-- Form for editing poli -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="nama_poli">Nama Poli</label>
            <input type="text" class="form-control" id="nama_poli" name="nama_poli" value="<?php echo $row_poli['nama_poli']; ?>" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $row_poli['keterangan']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <!-- Additional content goes here -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
