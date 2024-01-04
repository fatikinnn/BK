<?php
include '../../koneksi.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id_dokter = $_GET['id'];

    // Hapus jadwal_periksa yang terkait dengan dokter
    $query_delete_jadwal = "DELETE FROM jadwal_periksa WHERE id_dokter = $id_dokter";
    
    if (mysqli_query($koneksi, $query_delete_jadwal)) {
        // Hapus dokter setelah menghapus jadwal_periksa
        $query_delete_dokter = "DELETE FROM dokter WHERE id = $id_dokter";

        if (mysqli_query($koneksi, $query_delete_dokter)) {
            // Redirect to the original page after deletion
            header("Location: dokter.php");
            exit();
        } else {
            echo "Error menghapus dokter: " . mysqli_error($koneksi);
        }
    } else {
        echo "Error menghapus jadwal periksa: " . mysqli_error($koneksi);
    }
} else {
    echo "Parameter 'id' tidak ditemukan.";
}
?>
