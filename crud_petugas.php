<?php
session_start();
require_once "mysql.php";

// Periksa apakah pengguna telah login sebagai admin
if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Tambahkan petugas baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Proses penambahan petugas
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];
    // Lakukan validasi data
    // ...
    // Simpan data petugas ke database
    $sql = "INSERT INTO petugas (username, password, level) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $level);
    if (mysqli_stmt_execute($stmt)) {
        // Redirect ke halaman dashboard atau halaman lain yang sesuai
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Gagal menambahkan petugas: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Hapus petugas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id_petugas = $_POST['id_petugas'];
    $sql = "DELETE FROM petugas WHERE id_petugas=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_petugas);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: crud_petugas.php");
        exit;
    } else {
        echo "Gagal menghapus petugas: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Ambil daftar petugas dari database
$sql = "SELECT * FROM petugas";
$result = mysqli_query($conn, $sql);

// Jika query berhasil dijalankan
if ($result && mysqli_num_rows($result) > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CRUD Petugas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet"/>
    </head>
    <body>
        <h2>CRUD Petugas</h2>
        <div class="container">
            <h3>Tambah Petugas Baru</h3>
            <form action="" method="POST">
                <div class="mb-3 row">
                    <label for="username" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" id="username" name="username" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="password">Password</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="password" id="password" name="password" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label" for="level">Level:</label>
                    <div class="col-sm-10">
                        <select class="form-select" name="level" id="level" required>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Tambah</button>
            </form>
            <div class="mt-5">
                <h3>Daftar Petugas</h3>
                <table class="table align-middle table-hover">
                    <tr>
                        <th>ID Petugas</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    // Tampilkan data petugas dalam bentuk tabel
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['id_petugas']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['level']; ?></td>
                            <td class="d-flex gap-2">
                                <a class="btn btn-primary" href="edit_petugas.php?id=<?php echo $row['id_petugas']; ?>">Edit</a>
                                <form action="" method="POST">
                                    <input type="hidden" name="id_petugas" value="<?php echo $row['id_petugas']; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    </body>
    </html>
    <?php
} else {
    echo "Tidak ada data petugas yang tersedia.";
}
?>
