<?php
// r_pasien.php
session_start();

include_once("../../koneksi.php");

        // Periksa apakah dokter sudah login
        if (!isset($_SESSION['id'])) {
            // Redirect jika dokter belum login
            header("Location: ../login.php");
            exit();
        }

        // Ambil ID dokter dari sesi
        $id_dokter = $_SESSION['id'];

        $query = "SELECT *
        FROM pasien";
        $result = mysqli_query($koneksi, $query);
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dokter | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../dokter.php" class="nav-link">Home</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">Dokter</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
              <a href="../periksa/jadwal_periksa.php" class="nav-link active">
              <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
              <p>
                Jadwal Periksa
              </p>
            </a>
            <li class="nav-item menu-open">
            <a href="../m_pasien/m_pasien.php" class="nav-link active">
              <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
              <p>
                Memeriksa Pasien
              </p>
            </a>
            <a href="r_pasien.php" class="nav-link active">
              <!--<i class="nav-icon fas fa-tachometer-alt"></i>-->
              <p>
                Riwayat Pasien
              </p>
            </a>
              </li>
            </ul>
          </li>
      </nav>
        <!-- Menu Logout -->
        <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="../../logout.php" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                    Logout
                </p>
            </a>
        </li>
    </ul>
</nav>
      <!-- /.sidebar-menu -->
    </div>
    
    <!-- /.sidebar -->
  </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Riwayat Periksa Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  
  <!-- Content Wrapper. Contains page content -->
      
    <!-- Content Header (Page header) -->
    <!-- Tabel untuk menampilkan daftar obat -->
    
    <div class="container-fluid">
<section>
<div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Periksa Pasien</h3>
  </div>
  <table class="table table-bordered">
    <thead>
              <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. KTP</th>
                <th>No. HP</th>
                <th>No. RM</th>
              </tr>
    </thead>
    <tbody>

              <?php
              // Menampilkan data pasien dalam bentuk tabel
              $no = 1; // Initialize $no here
              if ($result->num_rows == 0) {
                echo "<tr><td colspan='7' align='center'>Tidak ada data</td></tr>";
            } else {
                while ($d = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $d['nama']; ?></td>
                        <td><?= $d['alamat']; ?></td>
                        <td><?= $d['no_ktp']; ?></td>
                        <td><?= $d['no_hp']; ?></td>
                        <td><?= $d['no_rm']; ?></td>
                        <td>
                            <button data-toggle="modal" data-target="#detailModal<?= $d['id'] ?>" class="btn btn-warning">Detail Riwayat Periksa
                            </button>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
$result->data_seek(0);
while ($d = $result->fetch_assoc()) {
    $no_detail = 1;
    $pasien_id = $d['id'];
    $data2 = $koneksi->query("SELECT 
                p.nama AS 'nama_pasien',
                pr.*,
                d.nama AS 'nama_dokter',
                dpo.keluhan AS 'keluhan',
                GROUP_CONCAT(o.nama_obat SEPARATOR ', ') AS 'obat'
                FROM periksa pr
                LEFT JOIN daftar_poli dpo ON (pr.id_daftar_poli = dpo.id)
                LEFT JOIN jadwal_periksa jp ON (dpo.id_jadwal = jp.id)
                LEFT JOIN dokter d ON (jp.id_dokter = d.id)
                LEFT JOIN pasien p ON (dpo.id_pasien = p.id)
                LEFT JOIN detail_periksa dp ON (pr.id = dp.id_periksa)
                LEFT JOIN obat o ON (dp.id_obat = o.id)
                WHERE dpo.id_pasien = '$pasien_id'
                GROUP BY pr.id
                ORDER BY pr.tgl_periksa DESC;");
?>
    <div class="modal fade" id="detailModal<?= $d['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalScrollableTitle">Riwayat <?= $d['nama'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($data2->num_rows == 0) : ?>
                        <p class="my-2 text-danger">Tidak Ditemukan Riwayat Periksa</p>
                    <?php else : ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Tanggal Periksa</th>
                                    <th scope="col">Nama Pasien</th>
                                    <th scope="col">Nama Dokter</th>
                                    <th scope="col">Keluhan</th>
                                    <th scope="col">Catatan</th>
                                    <th scope="col">Obat</th>
                                    <th scope="col">Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($da = $data2->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $no_detail++; ?></td>
                                        <td><?= $da['tgl_periksa']; ?></td>
                                        <td><?= $da['nama_pasien']; ?></td>
                                        <td><?= $da['nama_dokter']; ?></td>
                                        <td><?= $da['keluhan']; ?></td>
                                        <td><?= $da['catatan']; ?></td>
                                        <td><?= $da['obat']; ?></td>
                                        <td><?= $da['biaya_periksa']; ?></td>
                                    </tr>
                                <?php endwhile ?>
                            </tbody>
                        </table>
                    <?php endif ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard.js"></script>
</body>

</html>
