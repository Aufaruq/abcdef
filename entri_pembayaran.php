<?php
session_start();
require_once "mysql.php"; // Menghubungkan ke database

// Periksa apakah pengguna telah login dan memiliki peran yang sesuai
// if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
//     // Redirect pengguna yang belum login atau tidak memiliki peran yang sesuai
//     header("Location: login.php");
//     exit;
// }

// Periksa peran pengguna
// $allowed_roles = array("admin", "petugas");
// if (!in_array($_SESSION['user']['role'], $allowed_roles)) {
//     // Redirect pengguna yang tidak memiliki peran yang sesuai
//     echo "Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.";
//     exit;
// }

// Proses Tambah Transaksi Pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_transaksi'])) {
    $id_petugas = $_SESSION['user']['id_petugas']; // Ambil ID petugas dari sesi pengguna
    $nisn = $_POST['nisn'];
    $bulan_dibayar = $_POST['bulan_dibayar'];
    $tahun_dibayar = $_POST['tahun_dibayar'];
    $id_spp = $_POST['id_spp'];
    $jumlah_bayar = $_POST['jumlah_bayar'];

    // Query untuk menambahkan transaksi pembayaran
    $sql = "INSERT INTO pembayaran (id_petugas, nisn, tgl_bayar, bulan_dibayar, tahun_dibayar, id_spp, jumlah_bayar) VALUES (?, ?, CURRENT_DATE(), ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isiiii", $id_petugas, $nisn, $bulan_dibayar, $tahun_dibayar, $id_spp, $jumlah_bayar);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Transaksi pembayaran berhasil ditambahkan.";
        header('Location:lihat_history_pembayaran.php');
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Query untuk mendapatkan daftar siswa
$sql_siswa = "SELECT * FROM siswa";
$result_siswa = mysqli_query($conn, $sql_siswa);
$daftar_siswa = [];
if ($result_siswa && mysqli_num_rows($result_siswa) > 0) {
    while ($row = mysqli_fetch_assoc($result_siswa)) {
        $daftar_siswa[] = $row;
    }
}

// Query untuk mendapatkan daftar SPP
$sql_spp = "SELECT * FROM spp";
$result_spp = mysqli_query($conn, $sql_spp);
$daftar_spp = [];
if ($result_spp && mysqli_num_rows($result_spp) > 0) {
    while ($row = mysqli_fetch_assoc($result_spp)) {
        $daftar_spp[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entri Transaksi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <h2>Entri Transaksi Pembayaran</h2>

    <div class="container">
        <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Form Tambah Transaksi Pembayaran -->
        <h3>Tambah Transaksi Pembayaran</h3>
        <form action="" method="POST">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="nisn">NISN:</label>
                <div class="col-sm-10">
                    <select class="form-select" name="nisn" id="nisn" required>
                        <?php foreach ($daftar_siswa as $siswa): ?>
                        <option value="<?php echo $siswa['nisn']; ?>"><?php echo $siswa['nisn'] . ' - ' . $siswa['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="bulan_dibayar">Bulan Dibayar:</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" id="bulan_dibayar" name="bulan_dibayar" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="tahun_dibayar">Tahun Dibayar:</label>
                <div class="col-sm-10">
                    <input class="form-control" type="number" id="tahun_dibayar" name="tahun_dibayar" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="id_spp">ID SPP:</label>
                <div class="col-sm-10">
                    <select class="form-select" name="id_spp" id="id_spp" required>
                        <?php foreach ($daftar_spp as $spp): ?>
                        <option value="<?php echo $spp['id_spp']; ?>"><?php echo $spp['id_spp'] . ' - ' . $spp['tahun']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="jumlah_bayar">Jumlah Bayar:</label>
                <div class="col-sm-10">
                    <input class="form-control" type="number" id="jumlah_bayar" name="jumlah_bayar" required>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" name="tambah_transaksi">Tambah Transaksi Pembayaran</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
