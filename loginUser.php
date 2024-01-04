<?php
include '../../koneksi.php';

// Proses penambahan poli
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO poli (nama_poli, keterangan) VALUES ('$nama_poli', '$keterangan')";

    if (mysqli_query($koneksi, $query)) {
        echo "";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}

// Query untuk menampilkan daftar poli
$query_daftar_poli = "SELECT * FROM poli";
$result_daftar_poli = mysqli_query($koneksi, $query_daftar_poli);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Poli</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Daftar Poli</h2>

                <!-- Form untuk menambahkan poli -->
                <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_poli">Nama Poli:</label>
                    <input type="text" class="form-control" id="nama_poli" name="nama_poli" required>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan:</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                </div>

                    <button type="submit" class="btn btn-primary">Tambah Poli</button>
                </form>

                <hr>
                <!-- Tabel untuk menampilkan daftar poli -->
                <h3 class="text-center mb-4">Daftar Poli</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Poli</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result_daftar_poli)) {
                            echo "<tr>";
                            echo "<td>" . $no . "</td>"; // Menggunakan $no sebagai nomor urut
                            echo "<td>" . $row['nama_poli'] . "</td>";
                            echo "<td>" . $row['keterangan'] . "</td>";
                            echo "<td>
                                    <a href='edit_poli.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='hapus_poli.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus poli ini?\")'>Hapus</a>
                                  </td>";
                            echo "</tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
