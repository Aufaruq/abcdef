<?php
session_start();
require_once "mysql.php";

// Periksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Periksa apakah parameter NISN telah diberikan
if (!isset($_GET['nisn'])) {
    header("Location: crud_siswa.php");
    exit;
}

$nisn = $_GET['nisn'];

// Periksa apakah siswa dengan NISN yang diberikan ada di database
$sql = "SELECT * FROM siswa WHERE nisn = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $nisn);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Siswa tidak ditemukan.";
    exit;
}

$siswa = mysqli_fetch_assoc($result);

// Proses penyuntingan data siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $id_spp = $_POST['id_spp'];

    // Query untuk memperbarui data siswa
    $sql = "UPDATE siswa SET nis=?, nama=?, id_kelas=?, alamat=?, no_telp=?, id_spp=? WHERE nisn=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssisssi", $nis, $nama, $id_kelas, $alamat, $no_telp, $id_spp, $nisn);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data siswa berhasil diperbarui.";
        // Redirect ke halaman daftar siswa setelah penyuntingan selesai
        header("Location: crud_siswa.php");
        exit;
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Ambil daftar kelas untuk dropdown
$sql = "SELECT * FROM kelas";
$result_kelas = mysqli_query($conn, $sql);
$daftar_kelas = [];
if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
    while ($row = mysqli_fetch_assoc($result_kelas)) {
        $daftar_kelas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <h2>Edit Data Siswa</h2>

    <?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Form Edit Siswa -->
    <div class="container">
    <form action="" method="POST">
        <div class="mb-3 row">
            <input type="hidden" name="nisn" value="<?php echo $siswa['nisn']; ?>">
            <label class="col-sm-2 col-form-label" for="nis">NIS</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" id="nis" name="nis" value="<?php echo $siswa['nis']; ?>" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" for="nama">Nama</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" id="nama" name="nama" value="<?php echo $siswa['nama']; ?>" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" for="id_kelas">Kelas</label>
            <div class="col-sm-10">
                <select  class="form-select" name="id_kelas" id="id_kelas" required>
                    <?php foreach ($daftar_kelas as $kelas): ?>
                    <option value="<?php echo $kelas['id_kelas']; ?>" <?php if ($kelas['id_kelas'] == $siswa['id_kelas']) echo "selected"; ?>><?php echo $kelas['nama_kelas']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="3" required><?php echo $siswa['alamat']; ?></textarea>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="no_telp" class="col-sm-2 col-form-label">No. Telp</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" id="no_telp" name="no_telp" value="<?php echo $siswa['no_telp']; ?>" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="id_spp" class="col-sm-2 col-form-label">ID SPP</label>
            <div class="col-sm-10">
                <input class="form-control" type="number" id="id_spp" name="id_spp" value="<?php echo $siswa['id_spp']; ?>" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Simpan Perubahan</button>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
