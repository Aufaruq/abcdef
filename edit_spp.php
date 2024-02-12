<?php
session_start();
require_once "mysql.php";

// Cek apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Periksa apakah parameter id_spp telah diberikan
if (!isset($_POST['id_spp'])) {
    header("Location: crud_spp.php");
    exit;
}

$id_spp = $_POST['id_spp'];

// Proses penyuntingan data SPP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_spp'])) {
    $tahun = $_POST['tahun'];
    $nominal = $_POST['nominal'];

    // Query untuk memperbarui data SPP
    $sql = "UPDATE spp SET tahun=?, nominal=? WHERE id_spp=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isi", $tahun, $nominal, $id_spp);
    
    if (mysqli_stmt_execute($stmt)) {
        $success_message = "Data SPP berhasil diperbarui.";
        // Redirect ke halaman daftar SPP setelah penyuntingan selesai
        header("Location: crud_spp.php");
        exit;
    } else {
        $error_message = "Terjadi kesalahan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Query untuk mendapatkan data SPP yang akan diedit
$sql = "SELECT * FROM spp WHERE id_spp = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_spp);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Data SPP tidak ditemukan.";
    exit;
}

$spp = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data SPP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <h2>Edit Data SPP</h2>
    <div class="container">
        <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Form Edit SPP -->
        <form action="" method="POST">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="tahun">Tahun</label><br>
                <div class="col-sm-10">
                    <input class="form-control" type="hidden" name="id_spp" value="<?php echo $spp['id_spp']; ?>">
                </div>
            </div>
            <div class="mb-3 row">
                
            </div>
            <input type="number" id="tahun" name="tahun" value="<?php echo $spp['tahun']; ?>" required><br>
            <label for="nominal">Nominal:</label><br>
            <input type="number" id="nominal" name="nominal" value="<?php echo $spp['nominal']; ?>" required><br>
            <button type="submit" name="edit_spp">Simpan Perubahan</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
