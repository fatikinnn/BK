<?php
// Sertakan file koneksi.php
include "../../koneksi.php";

// Periksa apakah parameter id_pasien telah diterima
if (isset($_GET['id_pasien'])) {
    // Ambil nilai id_pasien dari parameter URL
    $id_pasien = $_GET['id_pasien'];

    // Query untuk mengambil detail pasien berdasarkan id_pasien
    $query_detail = "SELECT * FROM pasien WHERE id = $id_pasien";
    $result_detail = $koneksi->query($query_detail);

    // Query untuk mengambil daftar obat
    $query_obat = "SELECT * FROM obat";
    $result_obat = $koneksi->query($query_obat);

    // Query untuk mengambil id_daftar_poli berdasarkan id_pasien
    $query_id_daftar_poli = "SELECT id FROM daftar_poli WHERE id_pasien = $id_pasien";
    $result_id_daftar_poli = $koneksi->query($query_id_daftar_poli);

    // Periksa apakah query berhasil dijalankan
    if ($result_detail === false || $result_obat === false || $result_id_daftar_poli === false) {
        die("Error: " . $koneksi->error);
    }

    // Periksa apakah ada data yang ditemukan
    if ($result_detail->num_rows > 0) {
        // Ambil data pasien
        $row_detail = $result_detail->fetch_assoc();
        $nama_pasien = $row_detail["nama"];

        // Ambil id_daftar_poli
        if ($result_id_daftar_poli->num_rows > 0) {
            $row_id_daftar_poli = $result_id_daftar_poli->fetch_assoc();
            $id_daftar_poli = $row_id_daftar_poli['id'];
        } else {
            // Handle jika data daftar_poli tidak ditemukan
            echo "Data daftar_poli tidak ditemukan.";
            exit;
        }

        // Tutup koneksi hasil detail dan id_daftar_poli
        $result_detail->close();
        $result_id_daftar_poli->close();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Formulir Periksa</title>

            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('.select2').select2();

                    // Fungsi untuk menghitung Total Harga saat obat dipilih atau dihapus
                    function hitungTotalBiaya() {
                        var totalBiaya = 0;
                        $('select[name="obat[]"] option:selected').each(function () {
                            totalBiaya += parseInt($(this).data('harga'));
                        });

                        // Menambahkan biaya periksa ke total biaya obat
                        var biayaPeriksa = 150000; // Biaya periksa tetap
                        var totalBiayaPeriksa = totalBiaya + biayaPeriksa;

                        // Menampilkan total biaya periksa di input dan biaya periksa
                        $('#total_biaya_obat').val('Rp ' + number_format(totalBiaya, 0, ',', '.'));
                        $('#total_biaya_periksa').val('Rp ' + number_format(totalBiayaPeriksa, 0, ',', '.'));

                        // Meng-update nilai input hidden 'total_biaya_periksa'
                        $('input[name="total_biaya_periksa"]').val(totalBiayaPeriksa);
                    }

                    // Panggil fungsi saat halaman dimuat
                    hitungTotalBiaya();

                    // Event listener untuk perubahan pada select obat
                    $('select[name="obat[]"]').on('change', function () {
                        hitungTotalBiaya();
                    });
                });

                // Fungsi untuk format angka ke format Rupiah
                function number_format(number, decimals, dec_point, thousands_sep) {
                    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                    var n = !isFinite(+number) ? 0 : +number,
                        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                        s = '',
                        toFixedFix = function (n, prec) {
                            var k = Math.pow(10, prec);
                            return '' + Math.round(n * k) / k;
                        };

                    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                    if (s[0].length > 3) {
                        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                    }
                    if ((s[1] || '').length < prec) {
                        s[1] = s[1] || '';
                        s[1] += new Array(prec - s[1].length + 1).join('0');
                    }

                    return s.join(dec);
                }
            </script>
        </head>
        <body>
            <h2>Detail Pasien</h2>
            <p>Nama Pasien: <?php echo $nama_pasien; ?></p>

            <!-- Formulir untuk memasukkan tanggal dan jam periksa, catatan, dan obat -->
            <form action='simpan_periksa.php' method='post'>
                <input type='hidden' name='id_daftar_poli' value='<?php echo $id_daftar_poli; ?>'>

                <label for='tanggal_periksa'>Tanggal dan Jam Periksa:</label>
                <input type='datetime-local' name='tanggal_periksa' required>
                <br><br>

                <label for='catatan'>Catatan Periksa:</label>
                <textarea name='catatan' rows='4' cols='50'></textarea>
                <br><br>

                <label for='obat'>Pilih Obat:</label>
                <select multiple="multiple" class="select2" name='obat[]' required>
                    <?php
                    // Menampilkan opsi dropdown untuk obat dengan harga dan kemasan
                    while ($row_obat = $result_obat->fetch_assoc()) {
                        $harga_rp = "Rp " . number_format($row_obat['harga'], 0, ',', '.');
                        echo "<option value='" . $row_obat['id'] . "' data-harga='" . $row_obat['harga'] . "'>" . $row_obat['nama_obat'] . " - " . $harga_rp . " - " . $row_obat['kemasan'] . "</option>";
                    }
                    ?>
                </select>
                <br><br>

                <label for='total_biaya_obat'>Total Biaya Obat:</label>
                <input type='text' id='total_biaya_obat' name='total_biaya_obat' readonly>
                <br><br>

                <label for='total_biaya_periksa'>Total Biaya Periksa:</label>
                <input type='text' id='total_biaya_periksa' name='total_biaya_periksa' readonly value='Rp 0'>
                <br><br>

                <input type='submit' value='Simpan'>
            </form>
        </body>
        </html>

        <?php
        // Tutup koneksi hasil obat
        $result_obat->close();
    } else {
        echo "Data pasien tidak ditemukan.";
    }
} else {
    echo "Parameter id_pasien tidak ditemukan.";
}

// Tutup koneksi
$koneksi->close();
?>