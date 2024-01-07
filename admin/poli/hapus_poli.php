<?php
include '../../koneksi.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $id_poli = $_GET['id'];

    // Check for dependencies in the 'dokter' table
    $query_check_dependencies_dokter = "SELECT COUNT(*) as total FROM dokter WHERE id_poli = ?";
    $stmt_check_dependencies_dokter = mysqli_prepare($koneksi, $query_check_dependencies_dokter);
    mysqli_stmt_bind_param($stmt_check_dependencies_dokter, "i", $id_poli);
    mysqli_stmt_execute($stmt_check_dependencies_dokter);
    mysqli_stmt_bind_result($stmt_check_dependencies_dokter, $total_dependencies_dokter);
    mysqli_stmt_fetch($stmt_check_dependencies_dokter);
    mysqli_stmt_close($stmt_check_dependencies_dokter);

    if ($total_dependencies_dokter > 0) {
        // If there are dependencies in 'dokter', do not delete and show an error message
        echo "Tidak dapat menghapus poli karena terdapat dokter yang terkait.";
    } else {
        // Check for dependencies in the 'jadwal_periksa' table
        $query_check_dependencies_jadwal = "SELECT COUNT(*) as total FROM jadwal_periksa WHERE id_dokter IN (SELECT id FROM dokter WHERE id_poli = ?)";
        $stmt_check_dependencies_jadwal = mysqli_prepare($koneksi, $query_check_dependencies_jadwal);
        mysqli_stmt_bind_param($stmt_check_dependencies_jadwal, "i", $id_poli);
        mysqli_stmt_execute($stmt_check_dependencies_jadwal);
        mysqli_stmt_bind_result($stmt_check_dependencies_jadwal, $total_dependencies_jadwal);
        mysqli_stmt_fetch($stmt_check_dependencies_jadwal);
        mysqli_stmt_close($stmt_check_dependencies_jadwal);

        if ($total_dependencies_jadwal > 0) {
            // If there are dependencies in 'jadwal_periksa', do not delete and show an error message
            echo "Tidak dapat menghapus poli karena terdapat jadwal periksa yang terkait.";
        } else {
            // If there are no dependencies, proceed with deletion
            // Create the DELETE query for dokter
            $query_delete_dokter = "DELETE FROM dokter WHERE id_poli = ?";
            $stmt_delete_dokter = mysqli_prepare($koneksi, $query_delete_dokter);
            mysqli_stmt_bind_param($stmt_delete_dokter, "i", $id_poli);

            // Execute the query to delete dokter
            if (mysqli_stmt_execute($stmt_delete_dokter)) {
                // Now, proceed with the deletion of poli
                $query_delete_poli = "DELETE FROM poli WHERE id = ?";
                $stmt_delete_poli = mysqli_prepare($koneksi, $query_delete_poli);
                mysqli_stmt_bind_param($stmt_delete_poli, "i", $id_poli);

                // Execute the query to delete poli
                if (mysqli_stmt_execute($stmt_delete_poli)) {
                    // Redirect to the original page after deletion
                    header("Location: poli.php");
                    exit(); // Ensure no further code execution after the redirect
                } else {
                    echo "Error: " . $query_delete_poli . "<br>" . mysqli_error($koneksi);
                }

                mysqli_stmt_close($stmt_delete_poli);
            } else {
                echo "Error: " . $query_delete_dokter . "<br>" . mysqli_error($koneksi);
            }

            mysqli_stmt_close($stmt_delete_dokter);
        }
    }
} else {
    echo "Parameter 'id' tidak ditemukan.";
}

mysqli_close($koneksi);
?>
