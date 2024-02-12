<?php
session_start();
require_once "mysql.php"; // Menghubungkan ke database

// Fungsi untuk mendapatkan daftar kelas
function getDaftarKelas() {
    global $conn;
    $sql = "SELECT * FROM kelas";
    $result = mysqli_query($conn, $sql);
    $daftar_kelas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_kelas[] = $row;
    }
    return $daftar_kelas;
}

// Cek apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Proses Tambah Data Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_siswa'])) {
    $nisn = $_POST['nisn'];
    $nis = $_POST['nis'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $id_spp = $_POST['id_spp'];

    // Query untuk menambahkan data siswa
    $sql = "INSERT INTO siswa (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssiiss", $nisn, $nis, $nama, $id_kelas, $alamat, $no_telp, $id_spp);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data siswa berhasil ditambahkan.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Proses Edit Data Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_siswa'])) {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat = $_POST['alamat'];
    $no_telp = $_POST['no_telp'];
    $id_spp = $_POST['id_spp'];

    // Query untuk mengupdate data siswa
    $sql = "UPDATE siswa SET nama=?, id_kelas=?, alamat=?, no_telp=?, id_spp=? WHERE nisn=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisssi", $nama, $id_kelas, $alamat, $no_telp, $id_spp, $nisn);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data siswa berhasil diupdate.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Proses Hapus Data Siswa
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_siswa'])) {
    $nisn = $_POST['nisn'];

    // Query untuk menghapus data siswa
    $sql = "DELETE FROM siswa WHERE nisn=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nisn);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data siswa berhasil dihapus.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Query untuk mendapatkan daftar siswa
$sql = "SELECT * FROM siswa";
$result = mysqli_query($conn, $sql);
$daftar_siswa = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_siswa[] = $row;
    }
}

// Mendapatkan daftar kelas untuk dropdown
$daftar_kelas = getDaftarKelas();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar bg-body-tertiary mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Data Siswa</a>
        </div>
    </nav>

    <?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Form Tambah Siswa -->
    <div class="container">
    <h3>Tambah Siswa</h3>
    <form action="" method="POST">
        <div class="mb-3 row">
            <label for="nisn" class="col-sm-2 col-form-label">NISN</label>
            <div class="col-sm-10">
                <input type="text" id="nisn" name="nisn" class="form-control" required>
            </div>
        </div>
        <div class="mb-3 row">          
            <label for="nis" class="col-sm-2 col-form-label">NIS</label>
            <div class="col-sm-10">
                <input type="text" id="nis" name="nis" class="form-control" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <input type="text" id="nama" name="nama" class="form-control" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="id_kelas" class="col-sm-2 col-form-label">Kelas</label>
            <div class="col-sm-10">
                <select class="form-select" name="id_kelas" id="id_kelas" required>
                    <?php foreach ($daftar_kelas as $kelas): ?>
                    <option value="<?php echo $kelas['id_kelas']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="alamat" name="alamat" cols="30" rows="3" required></textarea> <!-- Menggunakan textarea untuk alamat -->
            </div>
        </div>
        <div class="mb-3 row">
            <label for="no_telp" class="col-sm-2 col-form-label">No. Telp</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" id="no_telp" name="no_telp" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="id_spp" class="col-sm-2 col-form-label">ID SPP</label>
            <div class="col-sm-10">
                <input class="form-control" type="number" id="id_spp" name="id_spp" required>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="tambah_siswa">Tambah Siswa</button>
    </form>

    <!-- Daftar Siswa -->
    <h3 class="mt-5">Daftar Siswa</h3>
    <table class="table table-hover align-middle" border="1">
        <thead>
            <tr>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>No. Telp</th>
                <th>ID SPP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftar_siswa as $siswa): ?>
            <tr>
                <td><?php echo $siswa['nisn']; ?></td>
                <td><?php echo $siswa['nis']; ?></td>
                <td><?php echo $siswa['nama']; ?></td>
                <td><?php echo $siswa['id_kelas']; ?></td>
                <td><?php echo $siswa['alamat']; ?></td>
                <td><?php echo $siswa['no_telp']; ?></td>
                <td><?php echo $siswa['id_spp']; ?></td>
                <td>
                    <form class="mb-1" action="edit_siswa.php" method="GET">
                        <input type="hidden" name="nisn" value="<?php echo $siswa['nisn']; ?>">
                        <button class="btn btn-success" type="submit" name="edit_siswa"><i class="ri-pencil-line"></i></button>
                    </form>
                    <form action="" method="POST">
                        <input type="hidden" name="nisn" value="<?php echo $siswa['nisn']; ?>">
                        <button class="btn btn-danger" type="submit" name="delete_siswa"><i class="ri-delete-bin-line"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
</html>
