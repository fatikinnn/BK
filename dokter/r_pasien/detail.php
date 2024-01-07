<?php
include_once("../../koneksi.php");

if (isset($_POST['id'])) {
    $id_periksa = $_POST['id'];

    $query = "SELECT periksa.tgl_periksa, dokter.nama as nama_dokter, daftar_poli.keluhan, periksa.catatan, obat.nama_obat, periksa.biaya_periksa
              FROM periksa
              JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id
              JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id
              JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
              LEFT JOIN detail_periksa ON periksa.id = detail_periksa.id_periksa
              LEFT JOIN obat ON detail_periksa.id_obat = obat.id
              WHERE periksa.id = $id_periksa";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $detail = mysqli_fetch_assoc($result);
        echo json_encode($detail);
    } else {
        echo json_encode(['error' => 'Gagal mengambil detail periksa.']);
    }
} else {
    echo json_encode(['error' => 'ID periksa tidak valid.']);
}
?>
