<?php
session_start();
require_once "mysql.php"; // Menghubungkan ke database

// Cek apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Proses Tambah Data SPP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_spp'])) {
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    // Query untuk menambahkan data SPP
    $sql = "INSERT INTO spp (tahun, nominal) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $tahun, $nominal);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data SPP berhasil ditambahkan.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Proses Edit Data SPP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_spp'])) {
    $id_spp = $_POST['id_spp'];
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    // Query untuk mengupdate data SPP
    $sql = "UPDATE spp SET tahun=?, nominal=? WHERE id_spp=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $tahun, $nominal, $id_spp);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data SPP berhasil diupdate.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Proses Hapus Data SPP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_spp'])) {
    $id_spp = $_POST['id_spp'];

    // Query untuk menghapus data SPP
    $sql = "DELETE FROM spp WHERE id_spp=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_spp);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data SPP berhasil dihapus.";
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Query untuk mendapatkan daftar SPP
$sql = "SELECT * FROM spp";
$result = mysqli_query($conn, $sql);
$daftar_spp = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $daftar_spp[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
    <h2>CRUD Data SPP</h2>

    <div class="container">
    <?php if (isset($success_message)): ?>
    <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Form Tambah SPP -->
    <h3>Tambah SPP</h3>
    <form action="" method="POST">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" for="tahun">Tahun:</label><br>
            <div class="col-sm-10">
                <input class="form-control" type="number" id="tahun" name="tahun" required><br>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label" for="nominal">Nominal:</label><br>
            <div class="col-sm-10">
                <input class="form-control" type="number" id="nominal" name="nominal" required><br>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="tambah_spp">Tambah SPP</button>
    </form>

    <!-- Daftar SPP -->
        <div class="mt-5">
        <h3>Daftar SPP</h3>
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>ID SPP</th>
                    <th>Tahun</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftar_spp as $spp): ?>
                <tr>
                    <td><?php echo $spp['id_spp']; ?></td>
                    <td><?php echo $spp['tahun']; ?></td>
                    <td><?php echo $spp['nominal']; ?></td>
                    <td class="d-flex gap-2">
                        <form action="edit_spp.php" method="POST">
                            <input type="hidden" name="id_spp" value="<?php echo $spp['id_spp']; ?>">
                            <button class="btn btn-primary" type="submit" name="edit_spp.php"><i class="ri-pencil-line"></i></button>
                        </form>
                        <form action="" method="POST">
                            <input type="hidden" name="id_spp" value="<?php echo $spp['id_spp']; ?>">
                            <button class="btn btn-danger" type="submit" name="delete_spp"><i class="ri-delete-bin-line"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
