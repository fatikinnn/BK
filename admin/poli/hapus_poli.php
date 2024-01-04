<?php
include '../../koneksi.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id_poli = $_GET['id'];

    // Create the DELETE query
    $query_delete_poli = "DELETE FROM poli WHERE id = $id_poli";

    // Execute the query
    if (mysqli_query($koneksi, $query_delete_poli)) {
        // Redirect to the original page after deletion
        header("Location: poli.php");
        exit(); // Ensure no further code execution after the redirect
    } else {
        echo "Error: " . $query_delete_poli . "<br>" . mysqli_error($koneksi);
    }
} else {
    echo "Parameter 'id' tidak ditemukan.";
}
?>
