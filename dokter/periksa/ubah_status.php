<?php
session_start();

include_once("../../koneksi.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id_jadwal = $_GET["id"];

    // Query untuk mendapatkan status aktif saat ini
    $sql_get_status = "SELECT aktif FROM jadwal_periksa WHERE id = ?";
    $stmt_get_status = mysqli_prepare($koneksi, $sql_get_status);
    mysqli_stmt_bind_param($stmt_get_status, "i", $id_jadwal);
    mysqli_stmt_execute($stmt_get_status);
    $result_get_status = mysqli_stmt_get_result($stmt_get_status);

    if ($result_get_status && $row_get_status = mysqli_fetch_assoc($result_get_status)) {
        $status_sekarang = $row_get_status["aktif"];

        // Ubah status
        $status_baru = ($status_sekarang == 'Y') ? 'N' : 'Y';
        $sql_update_status = "UPDATE jadwal_periksa SET aktif = ? WHERE id = ?";
        $stmt_update_status = mysqli_prepare($koneksi, $sql_update_status);
        mysqli_stmt_bind_param($stmt_update_status, "si", $status_baru, $id_jadwal);

        if (mysqli_stmt_execute($stmt_update_status)) {
            $_SESSION['success_message'] = "Status jadwal periksa berhasil diubah.";
        } else {
            $_SESSION['error_message'] = "Gagal mengubah status jadwal periksa.";
        }
    } else {
        $_SESSION['error_message'] = "Data jadwal periksa tidak ditemukan.";
    }

    header("Location: jadwal_periksa.php");
    exit();
} else {
    header("Location: jadwal_periksa.php");
    exit();
}
?>